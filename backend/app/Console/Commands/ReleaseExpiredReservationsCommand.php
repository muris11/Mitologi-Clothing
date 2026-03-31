<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ReleaseExpiredReservationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:release-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release reserved stock for orders that have been pending for more than 30 minutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredOrders = \App\Models\Order::where('status', 'pending')
            ->where('created_at', '<', now()->subMinutes(30))
            ->get();

        $count = 0;
        foreach ($expiredOrders as $order) {
            try {
                \Illuminate\Support\Facades\DB::transaction(function () use ($order) {
                    $order = \App\Models\Order::lockForUpdate()->find($order->id);
                    if ($order->status === 'pending') {
                        $order->update(['status' => 'cancelled', 'notes' => 'Otomatis dibatalkan karena melebihi batas waktu pembayaran (30 menit).']);
                    }
                });
                $count++;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Gagal membatalkan pesanan kedaluwarsa {$order->order_number}: " . $e->getMessage());
            }
        }

        $this->info("Berhasil membatalkan {$count} pesanan kedaluwarsa dan melepaskan reservasi stok.");
        \Illuminate\Support\Facades\Log::info("ReleaseExpiredReservationsCommand: Membatalkan {$count} pesanan kedaluwarsa.");
    }
}
