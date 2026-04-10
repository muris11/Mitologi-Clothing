<!DOCTYPE html>
<html lang="id" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Update Status Pesanan - {{ config('app.name') }}</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
</head>
<body style="margin:0;padding:0;background-color:#f0f0f5;font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;-webkit-font-smoothing:antialiased;">

@php
    $statusConfig = [
        'pending'    => ['label' => 'Menunggu Pembayaran', 'color' => '#F59E0B', 'bgLight' => '#FEF3C7', 'icon' => '⏳', 'step' => 1],
        'processing' => ['label' => 'Sedang Diproses',     'color' => '#3B82F6', 'bgLight' => '#DBEAFE', 'icon' => '⚙️', 'step' => 2],
        'shipped'    => ['label' => 'Dalam Pengiriman',    'color' => '#8B5CF6', 'bgLight' => '#EDE9FE', 'icon' => '🚚', 'step' => 3],
        'completed'  => ['label' => 'Selesai',             'color' => '#10B981', 'bgLight' => '#D1FAE5', 'icon' => '✅', 'step' => 4],
        'cancelled'  => ['label' => 'Dibatalkan',          'color' => '#EF4444', 'bgLight' => '#FEE2E2', 'icon' => '❌', 'step' => 0],
    ];
    $current = $statusConfig[$order->status] ?? ['label' => ucfirst($order->status), 'color' => '#6B7280', 'bgLight' => '#F3F4F6', 'icon' => '📦', 'step' => 0];
    $accentColor = $current['color'];
    $navyColor = '#0f172a';
    $goldColor = '#d4af37';
    $userName = $order->user ? $order->user->name : ($order->shippingAddress->name ?? 'Pelanggan');
    $frontendUrl = config('app.frontend_url');
    $isCancelled = $order->status === 'cancelled';
    $steps = ['Pesanan Diterima', 'Diproses', 'Dikirim', 'Selesai'];
@endphp

<!-- Wrapper -->
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f0f0f5;">
    <tr>
        <td align="center" style="padding:32px 16px;">

            <!-- Email Container -->
            <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                <!-- Header -->
                <tr>
                    <td style="background:linear-gradient(135deg,{{ $navyColor }} 0%,#1e293b 100%);padding:40px 32px 32px;text-align:center;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td align="center" style="padding-bottom:24px;">
                                    <span style="font-size:28px;font-weight:800;letter-spacing:4px;color:#ffffff;text-transform:uppercase;">{{ config('app.name', 'MITOLOGI CLOTHING') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" style="padding-bottom:8px;">
                                    <span style="font-size:40px;line-height:1;">{{ $current['icon'] }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <span style="display:inline-block;padding:8px 24px;background-color:{{ $accentColor }};color:#ffffff;font-size:14px;font-weight:700;border-radius:24px;letter-spacing:1px;text-transform:uppercase;">
                                        {{ $current['label'] }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Greeting -->
                <tr>
                    <td style="padding:32px 32px 16px;">
                        <p style="margin:0;font-size:18px;color:{{ $navyColor }};font-weight:700;">
                            Halo, {{ $userName }}! 👋
                        </p>
                    </td>
                </tr>

                <!-- Status Message -->
                <tr>
                    <td style="padding:0 32px 24px;">
                        <p style="margin:0;font-size:15px;line-height:1.7;color:#475569;">
                            @if($order->status == 'pending')
                                Terima kasih telah berbelanja di <strong>{{ config('app.name') }}</strong>! Pesanan Anda telah kami terima dan menunggu pembayaran. Segera selesaikan pembayaran agar pesanan dapat kami proses.
                            @elseif($order->status == 'processing')
                                Pembayaran Anda telah dikonfirmasi! 🎉 Tim produksi kami sedang mempersiapkan pesanan Anda dengan penuh perhatian terhadap detail dan kualitas.
                            @elseif($order->status == 'shipped')
                                Kabar gembira! 🚀 Pesanan Anda telah dikirim dan sedang dalam perjalanan menuju alamat tujuan. Silakan pantau status pengiriman menggunakan nomor resi di bawah.
                            @elseif($order->status == 'completed')
                                Pesanan Anda telah diterima dengan baik! 🌟 Terima kasih telah mempercayakan kebutuhan clothing Anda kepada kami. Kami harap Anda puas dengan produknya!
                            @elseif($order->status == 'cancelled')
                                Pesanan Anda telah dibatalkan. Jika ini bukan keputusan Anda atau ada pertanyaan, jangan ragu untuk menghubungi tim kami.
                            @else
                                Status pesanan Anda telah diperbarui menjadi <strong>{{ ucfirst($order->status) }}</strong>.
                            @endif
                        </p>
                    </td>
                </tr>

                @if(!$isCancelled)
                <!-- Progress Tracker -->
                <tr>
                    <td style="padding:0 32px 32px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f8fafc;border-radius:12px;padding:24px;">
                            <tr>
                                <td style="padding:20px;">
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            @foreach($steps as $i => $stepLabel)
                                            @php
                                                $stepNum = $i + 1;
                                                $isActive = $stepNum <= $current['step'];
                                                $isCurrent = $stepNum === $current['step'];
                                                $dotColor = $isActive ? $accentColor : '#CBD5E1';
                                                $textColor = $isActive ? $navyColor : '#94A3B8';
                                                $dotSize = $isCurrent ? '28' : '20';
                                            @endphp
                                            <td align="center" style="width:25%;vertical-align:top;">
                                                <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td align="center" style="padding-bottom:8px;">
                                                            <div style="width:{{ $dotSize }}px;height:{{ $dotSize }}px;border-radius:50%;background-color:{{ $dotColor }};{{ $isCurrent ? 'box-shadow:0 0 0 4px '.str_replace('#', 'rgba(', $accentColor).',0.2);' : '' }}"></div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center">
                                                            <span style="font-size:11px;font-weight:{{ $isActive ? '700' : '500' }};color:{{ $textColor }};line-height:1.3;display:block;">{{ $stepLabel }}</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            @endforeach
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                @endif

                <!-- Order Info Card -->
                <tr>
                    <td style="padding:0 32px 8px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:2px solid {{ $accentColor }}20;border-radius:12px;overflow:hidden;">
                            <tr>
                                <td style="background-color:{{ $current['bgLight'] }};padding:16px 20px;border-bottom:1px solid {{ $accentColor }}15;">
                                    <span style="font-size:14px;font-weight:700;color:{{ $navyColor }};letter-spacing:0.5px;">📋 DETAIL PESANAN</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:20px;">
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="padding:6px 0;font-size:13px;color:#64748B;width:40%;">Nomor Pesanan</td>
                                            <td style="padding:6px 0;font-size:14px;color:{{ $navyColor }};font-weight:700;text-align:right;">#{{ $order->order_number }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:6px 0;font-size:13px;color:#64748B;">Tanggal Pesanan</td>
                                            <td style="padding:6px 0;font-size:14px;color:{{ $navyColor }};font-weight:500;text-align:right;">{{ $order->created_at->format('d M Y, H:i') }} WIB</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:6px 0;font-size:13px;color:#64748B;">Metode Pembayaran</td>
                                            <td style="padding:6px 0;font-size:14px;color:{{ $navyColor }};font-weight:500;text-align:right;">{{ ucfirst($order->payment_method ?? 'Transfer Bank') }}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding:6px 0;font-size:13px;color:#64748B;">Status</td>
                                            <td style="padding:6px 0;text-align:right;">
                                                <span style="display:inline-block;padding:4px 12px;background-color:{{ $current['bgLight'] }};color:{{ $accentColor }};font-size:12px;font-weight:700;border-radius:16px;">
                                                    {{ $current['label'] }}
                                                </span>
                                            </td>
                                        </tr>
                                        @if($order->tracking_number && $order->status === 'shipped')
                                        <tr>
                                            <td colspan="2" style="padding-top:16px;">
                                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:linear-gradient(135deg,{{ $navyColor }},#1e293b);border-radius:10px;">
                                                    <tr>
                                                        <td style="padding:16px 20px;">
                                                            <p style="margin:0 0 4px;font-size:11px;color:rgba(255,255,255,0.6);font-weight:600;letter-spacing:1px;text-transform:uppercase;">NOMOR RESI</p>
                                                            <p style="margin:0;font-size:18px;color:{{ $goldColor }};font-weight:800;letter-spacing:2px;">{{ $order->tracking_number }}</p>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        @endif
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Items -->
                <tr>
                    <td style="padding:24px 32px 8px;">
                        <p style="margin:0 0 16px;font-size:14px;font-weight:700;color:{{ $navyColor }};letter-spacing:0.5px;">🛒 RINCIAN PRODUK</p>
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                            @foreach($order->items as $item)
                            <tr>
                                <td style="padding:14px 0;border-bottom:1px solid #f1f5f9;">
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="vertical-align:top;">
                                                <p style="margin:0;font-size:14px;font-weight:600;color:{{ $navyColor }};">{{ $item->product_title }}</p>
                                                @if($item->variant_title)
                                                <p style="margin:4px 0 0;font-size:12px;color:#94A3B8;">{{ $item->variant_title }}</p>
                                                @endif
                                                <p style="margin:4px 0 0;font-size:12px;color:#94A3B8;">Qty: {{ $item->quantity }}</p>
                                            </td>
                                            <td align="right" style="vertical-align:top;white-space:nowrap;">
                                                <p style="margin:0;font-size:14px;font-weight:700;color:{{ $navyColor }};">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>

                <!-- Totals -->
                <tr>
                    <td style="padding:16px 32px 32px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f8fafc;border-radius:10px;padding:16px;">
                            <tr>
                                <td style="padding:16px 20px;">
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                        @if($order->subtotal)
                                        <tr>
                                            <td style="padding:4px 0;font-size:13px;color:#64748B;">Subtotal</td>
                                            <td align="right" style="padding:4px 0;font-size:13px;color:#64748B;">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                        @endif
                                        @if($order->shipping_cost > 0)
                                        <tr>
                                            <td style="padding:4px 0;font-size:13px;color:#64748B;">Ongkos Kirim</td>
                                            <td align="right" style="padding:4px 0;font-size:13px;color:#64748B;">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                        </tr>
                                        @endif
                                        @if($order->tax > 0)
                                        <tr>
                                            <td style="padding:4px 0;font-size:13px;color:#64748B;">Pajak</td>
                                            <td align="right" style="padding:4px 0;font-size:13px;color:#64748B;">Rp {{ number_format($order->tax, 0, ',', '.') }}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td colspan="2" style="padding:8px 0 0;">
                                                <div style="border-top:2px solid #e2e8f0;"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:12px 0 0;font-size:16px;font-weight:800;color:{{ $navyColor }};">Total</td>
                                            <td align="right" style="padding:12px 0 0;font-size:18px;font-weight:800;color:{{ $accentColor }};">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Shipping Address -->
                @if($order->shippingAddress)
                <tr>
                    <td style="padding:0 32px 32px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;">
                            <tr>
                                <td style="background-color:#f8fafc;padding:12px 20px;border-bottom:1px solid #e2e8f0;">
                                    <span style="font-size:13px;font-weight:700;color:{{ $navyColor }};letter-spacing:0.5px;">📍 ALAMAT PENGIRIMAN</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:16px 20px;">
                                    <p style="margin:0;font-size:14px;font-weight:600;color:{{ $navyColor }};">{{ $order->shippingAddress->name }}</p>
                                    <p style="margin:4px 0 0;font-size:13px;color:#64748B;line-height:1.5;">
                                        {{ $order->shippingAddress->phone }}<br>
                                        {{ $order->shippingAddress->address }}<br>
                                        {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->province }} {{ $order->shippingAddress->postal_code }}
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                @endif

                <!-- CTA Button -->
                <tr>
                    <td style="padding:0 32px 32px;text-align:center;">
                        <a href="{{ $frontendUrl }}/shop/account/orders/{{ $order->order_number }}"
                           style="display:inline-block;padding:16px 40px;background:linear-gradient(135deg,{{ $navyColor }},#1e293b);color:{{ $goldColor }};font-size:14px;font-weight:700;text-decoration:none;border-radius:12px;letter-spacing:0.5px;box-shadow:0 4px 16px rgba(15,23,42,0.3);">
                            Lihat Detail Pesanan →
                        </a>
                    </td>
                </tr>

                <!-- Help Section -->
                <tr>
                    <td style="padding:0 32px 32px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background:linear-gradient(135deg,#fefce8,#fef9c3);border-radius:10px;border:1px solid #fde68a;">
                            <tr>
                                <td style="padding:20px;">
                                    <p style="margin:0 0 8px;font-size:14px;font-weight:700;color:#92400E;">💬 Butuh Bantuan?</p>
                                    <p style="margin:0;font-size:13px;color:#a16207;line-height:1.6;">
                                        Hubungi tim kami via WhatsApp di <strong>+62 813-2217-0902</strong> atau balas email ini. Kami siap membantu Anda!
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background-color:{{ $navyColor }};padding:32px;text-align:center;">
                        <p style="margin:0 0 8px;font-size:16px;font-weight:800;color:#ffffff;letter-spacing:3px;text-transform:uppercase;">{{ config('app.name', 'MITOLOGI CLOTHING') }}</p>
                        <p style="margin:0 0 16px;font-size:12px;color:rgba(255,255,255,0.5);line-height:1.5;">
                            Vendor Clothing Terpercaya Asal Indramayu
                        </p>
                        <div style="border-top:1px solid rgba(255,255,255,0.1);padding-top:16px;">
                            <p style="margin:0;font-size:11px;color:rgba(255,255,255,0.35);line-height:1.6;">
                                &copy; {{ date('Y') }} {{ config('app.name', 'Mitologi Clothing') }}. All rights reserved.<br>
                                Desa Leuwigede, Kec. Widasari, Kab. Indramayu 45271
                            </p>
                        </div>
                    </td>
                </tr>

            </table>

            <!-- Unsubscribe note -->
            <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;">
                <tr>
                    <td style="padding:24px;text-align:center;">
                        <p style="margin:0;font-size:11px;color:#94A3B8;line-height:1.5;">
                            Email ini dikirim secara otomatis terkait pesanan Anda.<br>
                            Mohon jangan membalas email ini jika tidak ada pertanyaan.
                        </p>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>

</body>
</html>
