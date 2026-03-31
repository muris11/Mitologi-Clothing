<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Midtrans\Transaction;
use App\Models\Order;
use App\Models\WebhookEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MidtransService
{
  public function __construct()
  {
    Config::$serverKey = config('midtrans.server_key');
    if (empty(Config::$serverKey)) {
      Log::warning('Midtrans server key is not configured (MIDTRANS_SERVER_KEY).');
    }
    Config::$isProduction = config('midtrans.is_production');
    Config::$isSanitized = config('midtrans.is_sanitized');
    Config::$is3ds = config('midtrans.is_3ds');
  }

  public function createSnapToken(Order $order): string
  {
    $items = [];
    foreach ($order->items as $item) {
      $items[] = [
        'id' => $item->variant_id,
        'price' => (int) $item->price,
        'quantity' => $item->quantity,
        'name' => substr($item->product_title . ' - ' . $item->variant_title, 0, 50),
      ];
    }

    if ($order->shipping_cost > 0) {
      $items[] = [
        'id' => 'shipping',
        'price' => (int) $order->shipping_cost,
        'quantity' => 1,
        'name' => 'Ongkos Kirim',
      ];
    }

    $params = [
      'transaction_details' => [
        'order_id' => $order->midtrans_order_id,
        'gross_amount' => (int) $order->total,
      ],
      'item_details' => $items,
      'customer_details' => [
        'first_name' => $order->user->name,
        'email' => $order->user->email,
        'phone' => $order->user->phone ?? '',
        'shipping_address' => $order->shippingAddress ? [
          'first_name' => $order->shippingAddress->name,
          'phone' => $order->shippingAddress->phone,
          'address' => $order->shippingAddress->address,
          'city' => $order->shippingAddress->city,
          'postal_code' => $order->shippingAddress->postal_code,
          'country_code' => 'IDN',
        ] : null,
      ],
      'callbacks' => [
        'finish' => config('app.frontend_url', 'http://localhost:3000') . '/shop/account/orders/' . $order->order_number,
      ],
    ];

    return Snap::getSnapToken($params);
  }

  public function handleNotification(): array
  {
    $notification = new Notification();

    $transactionStatus = $notification->transaction_status;
    $paymentType = $notification->payment_type;
    $orderId = $notification->order_id;
    $fraudStatus = $notification->fraud_status ?? null;

    Log::info('Midtrans webhook received', [
      'order_id' => $orderId,
      'transaction_status' => $transactionStatus,
      'payment_type' => $paymentType,
      'fraud_status' => $fraudStatus,
    ]);

    // Verify Signature
    $statusCode = $notification->status_code;
    $grossAmount = $notification->gross_amount;
    $signatureKey = $notification->signature_key;
    $localSignature = hash('sha512', $orderId . $statusCode . $grossAmount . Config::$serverKey);

    Log::info('Signature verification', [
      'local_signature' => substr($localSignature, 0, 20) . '...',
      'midtrans_signature' => substr($signatureKey ?? '', 0, 20) . '...',
      'match' => hash_equals($localSignature, (string) $signatureKey),
    ]);

    if (!hash_equals($localSignature, (string) $signatureKey)) {
        Log::error('Kunci tanda tangan tidak valid.');
        throw new \Exception('Kunci tanda tangan tidak valid.');
    }

    $eventId = hash('sha256', $orderId . $transactionStatus . $statusCode);
    
    if (WebhookEvent::isDuplicate($eventId)) {
        Log::info('Duplicate webhook event, skipping', ['event_id' => $eventId]);
        return ['status' => 'duplicate', 'transaction_status' => $transactionStatus];
    }

    return DB::transaction(function () use ($orderId, $transactionStatus, $paymentType, $fraudStatus, $eventId, $notification) {
        $order = Order::where('midtrans_order_id', $orderId)->lockForUpdate()->firstOrFail();

        Log::info('Order found', ['order_id' => $order->id, 'current_status' => $order->status]);

        $oldStatus = $order->status;
        $newStatus = $this->mapTransactionStatus($transactionStatus, $fraudStatus);

        if ($newStatus && !$this->isValidTransition($order->status, $newStatus)) {
            Log::warning('Invalid status transition rejected', ['order_id' => $order->id, 'from' => $order->status, 'to' => $newStatus]);
            
            WebhookEvent::create([
                'event_id' => $eventId,
                'provider' => 'midtrans',
                'order_id' => $orderId,
                'transaction_status' => $transactionStatus,
                'payload' => (array) $notification,
                'processed_at' => now(),
            ]);

            return ['order' => $order, 'status' => 'invalid_transition'];
        }

        $order->midtrans_transaction_id = $notification->transaction_id;
        $order->midtrans_payment_type = $paymentType;
        $order->midtrans_status = $transactionStatus;
        $order->midtrans_response = (array) $notification;

        if ($newStatus) {
            $order->status = $newStatus;
            if ($newStatus === 'processing') {
                $order->payment_method = $paymentType;
                $order->paid_at = now();
                Log::info('Payment successful - status updated to processing');
            }
        }

        $order->save();

        Log::info('Order status updated', [
          'order_id' => $order->id,
          'old_status' => $oldStatus,
          'new_status' => $order->status,
        ]);

        WebhookEvent::create([
            'event_id' => $eventId,
            'provider' => 'midtrans',
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'payload' => (array) $notification,
            'processed_at' => now(),
        ]);

        return ['order' => $order, 'status' => $transactionStatus];
    });
  }

  public function isValidTransition(string $from, string $to): bool
  {
      $allowed = [
          'pending'    => ['processing', 'cancelled'],
          'processing' => ['shipped', 'cancelled', 'refunded'],
          'shipped'    => ['completed'],
          'completed'  => ['refunded'],
          'cancelled'  => [],
          'refunded'   => [],
      ];
      return in_array($to, $allowed[$from] ?? []);
  }

  public function mapTransactionStatus(string $txStatus, ?string $fraudStatus): ?string
  {
      if ($txStatus == 'capture' || $txStatus == 'settlement') {
          return ($fraudStatus == 'accept' || $fraudStatus === null) ? 'processing' : null;
      }
      if ($txStatus == 'pending') return null;
      if (in_array($txStatus, ['deny', 'cancel', 'expire'])) return 'cancelled';
      if ($txStatus == 'refund') return 'refunded';
      return null;
  }

  public function refundOrder(Order $order, string $reason = 'Refund Requested'): array
  {
      if (!$order->midtrans_order_id) {
          throw new \Exception('Pembayaran tidak terhubung ke ID transaksi Midtrans.');
      }

      $params = [
          'refund_key' => 'refund-' . $order->order_number . '-' . time(),
          'amount' => (int) $order->total,
          'reason' => $reason
      ];

      return Transaction::refund($order->midtrans_order_id, $params);
  }
}
