<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        $statusLabels = [
            'pending' => 'Menunggu Pembayaran',
            'processing' => 'Sedang Diproses',
            'shipped' => 'Dalam Pengiriman',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        $statusLabel = $statusLabels[$this->order->status] ?? ucfirst($this->order->status);

        return new Envelope(
            subject: '['.config('app.name', 'Mitologi Clothing').'] Pesanan #'.$this->order->order_number.' - '.$statusLabel,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order_status',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
