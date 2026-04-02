<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    private MidtransService $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Syncs payment status from Midtrans for a given order
     *
     * @return array ['message' => string, 'status' => string]
     *
     * @throws \Exception
     */
    public function syncPaymentStatus(User $user, string $orderNumber): array
    {
        return DB::transaction(function () use ($user, $orderNumber) {
            $order = $user->orders()->where('order_number', $orderNumber)->lockForUpdate()->firstOrFail();

            if ($order->status !== 'pending') {
                return ['message' => 'Status sudah diperbarui sebelumnya.', 'status' => $order->status];
            }

            if (! $order->midtrans_order_id || empty(config('midtrans.server_key'))) {
                throw new \Exception('Tidak bisa cek status Midtrans.');
            }

            try {
                $status = \Midtrans\Transaction::status($order->midtrans_order_id);
                $transactionStatus = $status->transaction_status ?? null;
                $fraudStatus = $status->fraud_status ?? null;
                $paymentType = $status->payment_type ?? null;

                $newStatus = $this->midtransService->mapTransactionStatus($transactionStatus, $fraudStatus);

                if ($newStatus && ! $this->midtransService->isValidTransition($order->status, $newStatus)) {
                    throw new \Exception('Transisi status tidak valid.');
                }

                if ($newStatus) {
                    $order->status = $newStatus;
                    if ($newStatus === 'processing') {
                        $order->midtrans_status = $transactionStatus;
                        $order->payment_method = $paymentType;
                        $order->paid_at = now();
                    } elseif ($newStatus === 'cancelled') {
                        $order->midtrans_status = $transactionStatus;
                    }
                    $order->save();
                }

                return ['message' => 'Status berhasil dicek.', 'status' => $order->status];
            } catch (\Exception $e) {
                Log::error('Gagal cek status Midtrans: '.$e->getMessage());
                throw new \Exception('Gagal cek status.');
            }
        });
    }

    /**
     * Processes a user's refund request
     *
     * @throws \Exception
     */
    public function processRefundRequest(User $user, string $orderNumber, string $reason): void
    {
        $order = $user->orders()->where('order_number', $orderNumber)->firstOrFail();

        if ($order->status !== 'processing') {
            throw new \Exception('Hanya pesanan dalam proses yang dapat diajukan refund.');
        }

        if (! is_null($order->refund_requested_at)) {
            throw new \Exception('Pengajuan refund sudah dikirim dan sedang menunggu konfirmasi admin.');
        }

        // Append note indicating user requested refund
        $note = $order->notes ? $order->notes."\n" : '';
        $note .= '[Refund Request]: '.$reason.' ('.now()->format('Y-m-d H:i').')';

        $order->update([
            'notes' => $note,
            'refund_reason' => $reason,
            'refund_requested_at' => now(),
        ]);
    }
}
