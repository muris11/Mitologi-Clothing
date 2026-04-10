<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Update Status Pesanan - Mitologi Clothing</title>
  <meta name="color-scheme" content="light">
  <meta name="supported-color-schemes" content="light">
  <style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

    :root {
      color-scheme: light;
      supported-color-schemes: light;
    }

    body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
    table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
    img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic; }
    table { border-collapse: collapse !important; }
    body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
    a[x-apple-data-detectors] { color: inherit !important; text-decoration: none !important; font-size: inherit !important; font-family: inherit !important; font-weight: inherit !important; line-height: inherit !important; }

    @media screen and (max-width: 620px) {
      .main-wrap { width: 100% !important; }
      .body-pad { padding: 32px 20px !important; }
      .col-half { display: block !important; width: 100% !important; padding-bottom: 16px !important; }
      .hide-mobile { display: none !important; }
    }

    /* Force Light Mode */
    @media (prefers-color-scheme: dark) {
      body, .body-bg { background-color: #F8FAFC !important; }
      .main-wrap { background-color: #FFFFFF !important; }
      h1, h2, p, span, td { color: #1A1612 !important; }
      .footer-bg { background-color: #F8FAFC !important; }
      .section-bg { background-color: #F1F5F9 !important; }
      .text-muted { color: #64748B !important; }
    }
  </style>
</head>
<body class="body-bg" style="margin:0;padding:0;background-color:#F8FAFC !important;font-family:'Plus Jakarta Sans',Helvetica,Arial,sans-serif;">

@php
  $statusConfig = [
    'pending'    => ['label' => 'Menunggu Pembayaran', 'dot' => '#D4A853', 'ink' => '#7A5C1E', 'wash' => '#FBF3E2'],
    'processing' => ['label' => 'Sedang Diproses',     'dot' => '#4A7FA5', 'ink' => '#1D4466', 'wash' => '#E6F0F8'],
    'shipped'    => ['label' => 'Dalam Pengiriman',    'dot' => '#6A5ACD', 'ink' => '#3B2F8C', 'wash' => '#EDEAFC'],
    'completed'  => ['label' => 'Selesai',             'dot' => '#4A9E7A', 'ink' => '#1F5C42', 'wash' => '#E4F5ED'],
    'cancelled'  => ['label' => 'Dibatalkan',          'dot' => '#C0544A', 'ink' => '#7A2020', 'wash' => '#FCE8E6'],
  ];
  $current = $statusConfig[$order->status] ?? ['label' => ucfirst($order->status), 'dot' => '#888', 'ink' => '#444', 'wash' => '#F5F5F5'];
  $userName = $order->user ? $order->user->name : ($order->shippingAddress->name ?? 'Pelanggan');
  $frontendUrl = config('app.frontend_url');
@endphp

<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td align="center" style="padding:48px 16px 56px;">

      <!-- CARD -->
      <table border="0" cellpadding="0" cellspacing="0" width="600" class="main-wrap"
             style="background-color:#FFFFFF !important;border-radius:20px;overflow:hidden;border:1px solid #E2E8F0;">

        <!-- ══ HEADER ══ -->
        <tr>
          <td style="background-color:#14193C;padding:36px 48px 32px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td>
                  <p style="margin:0;font-family:'Plus Jakarta Sans',Helvetica,Arial,sans-serif;font-size:22px;font-weight:800;color:#C9A84C;letter-spacing:0.08em;">
                    Mitologi
                  </p>
                  <p style="margin:4px 0 0;font-size:10px;color:#94A3B8;letter-spacing:0.25em;text-transform:uppercase;font-weight:500;">
                    Clothing
                  </p>
                </td>
                <td align="right" style="vertical-align:top;">
                  <!-- Status pill -->
                  <table border="0" cellpadding="0" cellspacing="0" style="display:inline-table;">
                    <tr>
                      <td style="padding:5px 14px;border-radius:100px;font-size:10px;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;color:{{ $current['ink'] }};background-color:{{ $current['wash'] }};">
                        <table border="0" cellpadding="0" cellspacing="0">
                          <tr>
                            <td style="padding-right:8px;vertical-align:middle;">
                            </td>
                            <td style="vertical-align:middle;">
                              {{ $current['label'] }}
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
            <!-- thin gold rule -->
            <div style="height:1px;background:linear-gradient(to right,#C9A84C,transparent);margin-top:28px;"></div>
          </td>
        </tr>

        <!-- ══ ICON STRIP ══ -->
        <tr>
          <td align="center" style="padding:40px 48px 0;">
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center"
                    style="width:48px;height:48px;background-color:#F1F5F9;border-radius:14px;border:1px solid #E2E8F0;text-align:center;vertical-align:middle;">
                  <img src="{{ url('images/emails/order-icon.png') }}" width="26" height="26" alt=""
                       style="display:block;margin:0 auto;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;">
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- ══ BODY ══ -->
        <tr>
          <td class="body-pad" style="padding:44px 48px;">

            <!-- Greeting -->
            <p style="margin:0 0 6px;font-family:'Plus Jakarta Sans',Helvetica,Arial,sans-serif;font-size:26px;font-weight:700;color:#1A1612 !important;line-height:1.2;">
              Halo, {{ $userName }}.
            </p>
            <p style="margin:0 0 28px;font-size:14px;color:#475569;line-height:1.7;">
              @if($order->status == 'pending')
                Pesanan Anda sudah kami terima. Selesaikan pembayaran sesegera mungkin agar proses produksi bisa segera kami mulai.
              @elseif($order->status == 'processing')
                Pembayaran berhasil terverifikasi. Tim kami sedang mengerjakan pesanan Anda dengan penuh perhatian.
              @elseif($order->status == 'shipped')
                Pesanan Anda sudah dalam perjalanan. Gunakan nomor resi di bawah untuk melacak pengiriman.
              @elseif($order->status == 'completed')
                Terima kasih sudah berbelanja bersama kami. Semoga produk kami menemani hari-hari Anda dengan baik.
              @elseif($order->status == 'cancelled')
                Pesanan ini telah dibatalkan. Jika ada pertanyaan, tim kami siap membantu.
              @else
                Status terbaru pesanan Anda: {{ $current['label'] }}.
              @endif
            </p>

            <!-- Order meta strip -->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="section-bg"
                   style="background-color:#F1F5F9 !important;border-radius:12px;margin-bottom:28px;">
              <tr>
                <td style="padding:20px 24px;">
                  <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                      <td class="col-half" width="50%" style="padding-right:16px;">
                        <p style="margin:0 0 3px;font-size:9px;font-weight:600;color:#9C8E7F;letter-spacing:0.2em;text-transform:uppercase;">Nomor Pesanan</p>
                        <p style="margin:0;font-size:14px;font-weight:600;color:#1A1612;letter-spacing:0.04em;">#{{ $order->order_number }}</p>
                      </td>
                      <td class="col-half" width="50%">
                        <p style="margin:0 0 3px;font-size:9px;font-weight:600;color:#9C8E7F;letter-spacing:0.2em;text-transform:uppercase;">Tanggal</p>
                        <p style="margin:0;font-size:14px;font-weight:500;color:#1A1612;">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding-top:16px;" class="col-half" width="50%">
                        <p style="margin:0 0 3px;font-size:9px;font-weight:600;color:#9C8E7F;letter-spacing:0.2em;text-transform:uppercase;">Metode Bayar</p>
                        <p style="margin:0;font-size:14px;font-weight:500;color:#1A1612;text-transform:uppercase;">{{ $order->payment_method ?? 'Transfer Bank' }}</p>
                      </td>
                      @if($order->tracking_number && $order->status === 'shipped')
                      <td style="padding-top:16px;" class="col-half" width="50%">
                        <p style="margin:0 0 3px;font-size:9px;font-weight:600;color:#9C8E7F;letter-spacing:0.2em;text-transform:uppercase;">Nomor Resi</p>
                        <p style="margin:0;font-size:14px;font-weight:600;color:#1A1612;">{{ $order->tracking_number }}</p>
                      </td>
                      @endif
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            <!-- Items -->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom:28px;">
              <!-- header row -->
              <tr>
                <td style="padding:0 0 10px;font-size:9px;font-weight:600;color:#64748B;letter-spacing:0.2em;text-transform:uppercase;border-bottom:1px solid #E2E8F0;">Item</td>
                <td style="padding:0 0 10px;text-align:center;font-size:9px;font-weight:600;color:#64748B;letter-spacing:0.2em;text-transform:uppercase;border-bottom:1px solid #E2E8F0;width:40px;">Qty</td>
                <td style="padding:0 0 10px;text-align:right;font-size:9px;font-weight:600;color:#64748B;letter-spacing:0.2em;text-transform:uppercase;border-bottom:1px solid #E2E8F0;">Harga</td>
              </tr>

              @foreach($order->items as $item)
              <tr>
                <td style="padding:14px 0;border-bottom:1px solid #F1F5F9;">
                  <p style="margin:0;font-size:14px;font-weight:500;color:#1A1612;">{{ $item->product_title }}</p>
                  @if($item->variant_title)
                  <p style="margin:3px 0 0;font-size:11px;color:#64748B;letter-spacing:0.05em;">{{ $item->variant_title }}</p>
                  @endif
                </td>
                <td style="padding:14px 0;text-align:center;font-size:13px;color:#475569;border-bottom:1px solid #F1F5F9;">{{ $item->quantity }}</td>
                <td style="padding:14px 0;text-align:right;font-size:14px;font-weight:500;color:#0F172A;white-space:nowrap;border-bottom:1px solid #F1F5F9;">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
              </tr>
              @endforeach

              <!-- Subtotals -->
              @if($order->subtotal)
              <tr>
                <td colspan="2" style="padding:12px 0 4px;text-align:right;font-size:12px;color:#64748B;">Subtotal</td>
                <td style="padding:12px 0 4px;text-align:right;font-size:13px;color:#0F172A;">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
              </tr>
              @endif
              @if($order->shipping_cost > 0)
              <tr>
                <td colspan="2" style="padding:4px 0;text-align:right;font-size:12px;color:#9C8E7F;">Ongkos Kirim</td>
                <td style="padding:4px 0;text-align:right;font-size:13px;color:#1A1612;">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
              </tr>
              @endif
              @if($order->tax > 0)
              <tr>
                <td colspan="2" style="padding:4px 0;text-align:right;font-size:12px;color:#9C8E7F;">Pajak</td>
                <td style="padding:4px 0;text-align:right;font-size:13px;color:#1A1612;">Rp {{ number_format($order->tax, 0, ',', '.') }}</td>
              </tr>
              @endif

              <!-- Grand total row -->
              <tr>
                <td colspan="3" style="padding:0;">
                  <table border="0" cellpadding="0" cellspacing="0" width="100%"
                         style="margin-top:12px;background-color:#14193C;border-radius:10px;overflow:hidden;">
                    <tr>
                      <td style="padding:16px 20px;font-size:10px;font-weight:600;color:#94A3B8;letter-spacing:0.2em;text-transform:uppercase;">Total Keseluruhan</td>
                      <td style="padding:16px 20px;text-align:right;font-family:'Plus Jakarta Sans',Helvetica,Arial,sans-serif;font-size:20px;font-weight:700;color:#C9A84C;white-space:nowrap;">
                        Rp {{ number_format($order->total, 0, ',', '.') }}
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            <!-- Shipping address -->
            @if($order->shippingAddress)
            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                   style="border:1px solid #E2E8F0;border-radius:12px;margin-bottom:36px;">
              <tr>
                <td style="padding:18px 22px;">
                  <p style="margin:0 0 8px;font-size:9px;font-weight:600;color:#64748B;letter-spacing:0.2em;text-transform:uppercase;">Tujuan Pengiriman</p>
                  <p style="margin:0 0 4px;font-size:14px;font-weight:600;color:#0F172A;">{{ $order->shippingAddress->name }}</p>
                  <p style="margin:0;font-size:13px;color:#475569;line-height:1.7;">
                    {{ $order->shippingAddress->phone }}<br>
                    {{ $order->shippingAddress->address }}<br>
                    {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->province }} {{ $order->shippingAddress->postal_code }}
                  </p>
                </td>
              </tr>
            </table>
            @endif

            <!-- CTA -->
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td align="center">
                  <a href="{{ $frontendUrl }}/shop/account/orders/{{ $order->order_number }}" target="_blank"
                     style="display:inline-block;padding:15px 40px;background-color:#14193C;color:#C9A84C;font-size:11px;font-weight:600;text-decoration:none;text-transform:uppercase;letter-spacing:0.18em;border-radius:100px;border:1px solid #C9A84C;">
                    Lihat Detail Pesanan &rarr;
                  </a>
                </td>
              </tr>
            </table>

          </td>
        </tr>

        <!-- ══ FOOTER ══ -->
        <tr>
          <td class="footer-bg" style="background-color:#F8FAFC !important;padding:28px 48px;border-top:1px solid #E2E8F0;">
            <p style="margin:0 0 4px;font-size:11px;color:#64748B;text-align:center;">
              &copy; {{ date('Y') }} Mitologi Clothing &mdash; Semua hak dilindungi
            </p>
            <p style="margin:0;font-size:11px;color:#94A3B8;text-align:center;line-height:1.7;">
              Email ini dibuat otomatis oleh sistem. Mohon tidak membalas langsung.
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>
