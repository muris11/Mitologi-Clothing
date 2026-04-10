<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="id">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
  <title>Update Status Pesanan - Mitologi Clothing</title>
  <meta name="color-scheme" content="light only">
  <meta name="supported-color-schemes" content="light">
  <style type="text/css">
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

    :root {
      color-scheme: light only;
      supported-color-schemes: light;
    }

    body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
    table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
    table { border-collapse: collapse !important; }
    body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #F8FAFC !important; }
    a[x-apple-data-detectors] { color: inherit !important; text-decoration: none !important; font-size: inherit !important; font-family: inherit !important; font-weight: inherit !important; line-height: inherit !important; }

    /* Premium Card Styling */
    .premium-card {
      background: linear-gradient(180deg, #FFFFFF 0%, #FAFBFC 100%) !important;
      border: 1px solid #E2E8F0 !important;
      box-shadow: 0 4px 24px rgba(30, 41, 59, 0.08), 0 1px 2px rgba(30, 41, 59, 0.04) !important;
    }

    .glass-header {
      background: linear-gradient(135deg, #1E293B 0%, #0F172A 50%, #1E293B 100%) !important;
    }

    .premium-btn {
      background: linear-gradient(135deg, #1E293B 0%, #334155 100%) !important;
      box-shadow: 0 4px 16px rgba(30, 41, 59, 0.2), 0 2px 4px rgba(30, 41, 59, 0.1) !important;
    }

    /* Mobile Responsive */
    @media screen and (max-width: 600px) {
      .main-wrap { width: 100% !important; max-width: 100% !important; }
      .body-pad { padding: 32px 24px !important; }
      .header-pad { padding: 32px 24px !important; }
      .brand-text { font-size: 18px !important; }
      .greeting-text { font-size: 22px !important; }
      .meta-box { padding: 20px !important; }
      .col-half { display: block !important; width: 100% !important; padding-right: 0 !important; padding-bottom: 12px !important; }
      .col-half-last { padding-bottom: 0 !important; }
      .premium-btn { padding: 14px 28px !important; font-size: 14px !important; display: block !important; text-align: center !important; }
      .footer-pad { padding: 28px 24px !important; }
    }

    @media screen and (max-width: 420px) {
      .body-pad { padding: 28px 20px !important; }
      .greeting-text { font-size: 20px !important; }
    }

    /* Force Light Mode - Override ALL dark mode attempts */
    @media (prefers-color-scheme: dark) {
      body { background-color: #F8FAFC !important; }
      .body-bg { background-color: #F8FAFC !important; }
      .main-wrap { 
        background: linear-gradient(180deg, #FFFFFF 0%, #FAFBFC 100%) !important; 
        border: 1px solid #E2E8F0 !important;
      }
      .glass-header {
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 50%, #1E293B 100%) !important;
      }
      h1, h2, h3, p, span, td, div { color: inherit !important; }
      .brand-text { color: #C9A84C !important; }
      .brand-sub { color: #94A3B8 !important; }
      .greeting-text { color: #0F172A !important; }
      .content-text { color: #334155 !important; }
      .text-muted { color: #64748B !important; }
      .text-subtle { color: #94A3B8 !important; }
      .meta-box { background-color: #F8FAFC !important; border-color: #F1F5F9 !important; }
      .item-table td { color: #334155 !important; border-color: #F1F5F9 !important; }
      .total-box { 
        background: linear-gradient(135deg, #1E293B 0%, #334155 100%) !important;
      }
      .total-label { color: #94A3B8 !important; }
      .total-amount { color: #C9A84C !important; }
      .footer-bg { background-color: #F8FAFC !important; }
      .premium-btn {
        background: linear-gradient(135deg, #1E293B 0%, #334155 100%) !important;
      }
    }
  </style>
</head>
<body class="body-bg" style="margin:0;padding:0;background-color:#F8FAFC;font-family:'Plus Jakarta Sans',-apple-system,BlinkMacSystemFont,Helvetica,Arial,sans-serif;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;">

@php
  $statusConfig = [
    'pending'    => ['label' => 'Menunggu Pembayaran', 'color' => '#059669', 'bg' => '#ECFDF5'],
    'processing' => ['label' => 'Sedang Diproses',     'color' => '#2563EB', 'bg' => '#EFF6FF'],
    'shipped'    => ['label' => 'Dalam Pengiriman',    'color' => '#7C3AED', 'bg' => '#F5F3FF'],
    'completed'  => ['label' => 'Selesai',             'color' => '#059669', 'bg' => '#ECFDF5'],
    'cancelled'  => ['label' => 'Dibatalkan',          'color' => '#DC2626', 'bg' => '#FEF2F2'],
  ];
  $current = $statusConfig[$order->status] ?? ['label' => ucfirst($order->status), 'color' => '#6B7280', 'bg' => '#F9FAFB'];
  $userName = $order->user ? $order->user->name : ($order->shippingAddress->name ?? 'Pelanggan');
  $frontendUrl = config('app.frontend_url');
  $pillBg = $current['bg'];
  $pillColor = $current['color'];
@endphp

<table border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td align="center" style="padding:32px 12px;">

      <!-- PREMIUM CARD -->
      <table border="0" cellpadding="0" cellspacing="0" width="600" class="main-wrap premium-card" style="max-width:600px;width:100%;background:linear-gradient(180deg, #FFFFFF 0%, #FAFBFC 100%);border-radius:24px;overflow:hidden;border:1px solid #E2E8F0;box-shadow:0 4px 24px rgba(30,41,59,0.08),0 1px 2px rgba(30,41,59,0.04);">

        <!-- ══ PREMIUM HEADER ══ -->
        <tr>
          <td class="header-pad glass-header" style="background:linear-gradient(135deg, #1E293B 0%, #0F172A 50%, #1E293B 100%);padding:32px 40px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td style="vertical-align:middle;">
                  <!-- Brand -->
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="vertical-align:middle;padding-right:12px;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke="#C9A84C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                      </td>
                      <td style="vertical-align:middle;">
                        <p class="brand-text" style="margin:0;font-family:'Plus Jakarta Sans',Helvetica,Arial,sans-serif;font-size:20px;font-weight:700;color:#C9A84C;letter-spacing:0.04em;">Mitologi</p>
                        <p class="brand-sub" style="margin:0;font-size:10px;color:#94A3B8;letter-spacing:0.2em;text-transform:uppercase;font-weight:600;">Clothing</p>
                      </td>
                    </tr>
                  </table>
                </td>
                <td align="right" style="vertical-align:middle;">
                  <!-- Status Pill -->
                  {!! '<span style="display:inline-block;padding:6px 14px;border-radius:20px;font-size:11px;font-weight:600;background-color:' . $pillBg . ';color:' . $pillColor . ';white-space:nowrap;">' . $current['label'] . '</span>' !!}
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- ══ PREMIUM BODY ══ -->
        <tr>
          <td class="body-pad" style="padding:40px 40px 32px;">

            <!-- Greeting -->
            <p class="greeting-text" style="margin:0 0 8px;font-family:'Plus Jakarta Sans',Helvetica,Arial,sans-serif;font-size:24px;font-weight:700;color:#0F172A;letter-spacing:-0.02em;line-height:1.3;">
              Halo, {{ $userName }}
            </p>
            <p class="content-text text-muted" style="margin:0 0 28px;font-size:15px;color:#64748B;line-height:1.7;">
              @if($order->status == 'pending')
                Pesanan Anda sudah kami terima. Selesaikan pembayaran agar kami bisa segera memprosesnya.
              @elseif($order->status == 'processing')
                Pembayaran berhasil terverifikasi. Tim kami sedang mengerjakan pesanan Anda dengan penuh perhatian.
              @elseif($order->status == 'shipped')
                Pesanan Anda sedang dalam perjalanan. Gunakan nomor resi untuk melacak pengiriman.
              @elseif($order->status == 'completed')
                Terima kasih telah berbelanja. Semoga produk kami menemani hari-hari Anda.
              @elseif($order->status == 'cancelled')
                Pesanan ini telah dibatalkan. Hubungi kami jika ada pertanyaan.
              @else
                Status terbaru pesanan Anda: {{ $current['label'] }}.
              @endif
            </p>

            <!-- Meta Box -->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="meta-box" style="background-color:#F8FAFC;border-radius:16px;margin-bottom:28px;border:1px solid #F1F5F9;">
              <tr>
                <td style="padding:24px;">
                  <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                      <td class="col-half" width="50%" style="padding-right:16px;">
                        <p class="text-subtle" style="margin:0 0 4px;font-size:11px;font-weight:600;color:#94A3B8;letter-spacing:0.02em;text-transform:uppercase;">Nomor Pesanan</p>
                        <p style="margin:0;font-size:15px;font-weight:700;color:#0F172A;">#{{ $order->order_number }}</p>
                      </td>
                      <td class="col-half col-half-last" width="50%">
                        <p class="text-subtle" style="margin:0 0 4px;font-size:11px;font-weight:600;color:#94A3B8;letter-spacing:0.02em;text-transform:uppercase;">Tanggal</p>
                        <p style="margin:0;font-size:15px;font-weight:500;color:#334155;">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                      </td>
                    </tr>
                    @if($order->tracking_number && $order->status === 'shipped')
                    <tr>
                      <td colspan="2" style="padding-top:16px;">
                        <p class="text-subtle" style="margin:0 0 4px;font-size:11px;font-weight:600;color:#94A3B8;letter-spacing:0.02em;text-transform:uppercase;">Nomor Resi</p>
                        <p style="margin:0;font-size:15px;font-weight:700;color:#0F172A;">{{ $order->tracking_number }}</p>
                      </td>
                    </tr>
                    @endif
                  </table>
                </td>
              </tr>
            </table>

            <!-- Items Table -->
            <table class="item-table" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom:24px;">
              <tr>
                <td style="padding:0 0 12px;font-size:11px;font-weight:600;color:#94A3B8;letter-spacing:0.02em;text-transform:uppercase;border-bottom:1px solid #E2E8F0;">Produk</td>
                <td style="padding:0 0 12px;text-align:center;font-size:11px;font-weight:600;color:#94A3B8;letter-spacing:0.02em;text-transform:uppercase;border-bottom:1px solid #E2E8F0;width:50px;">Qty</td>
                <td style="padding:0 0 12px;text-align:right;font-size:11px;font-weight:600;color:#94A3B8;letter-spacing:0.02em;text-transform:uppercase;border-bottom:1px solid #E2E8F0;">Harga</td>
              </tr>

              @foreach($order->items as $item)
              <tr>
                <td style="padding:14px 0;border-bottom:1px solid #F1F5F9;">
                  <p style="margin:0;font-size:15px;font-weight:500;color:#0F172A;">{{ $item->product_title }}</p>
                  @if($item->variant_title)
                  <p class="text-muted" style="margin:2px 0 0;font-size:13px;color:#64748B;">{{ $item->variant_title }}</p>
                  @endif
                </td>
                <td style="padding:14px 0;text-align:center;font-size:15px;color:#64748B;border-bottom:1px solid #F1F5F9;">{{ $item->quantity }}</td>
                <td style="padding:14px 0;text-align:right;font-size:15px;font-weight:500;color:#0F172A;white-space:nowrap;border-bottom:1px solid #F1F5F9;">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
              </tr>
              @endforeach

              <!-- Subtotals -->
              @if($order->subtotal)
              <tr>
                <td colspan="2" style="padding:12px 0 4px;text-align:right;font-size:14px;color:#64748B;">Subtotal</td>
                <td style="padding:12px 0 4px;text-align:right;font-size:14px;color:#334155;">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
              </tr>
              @endif
              @if($order->shipping_cost > 0)
              <tr>
                <td colspan="2" style="padding:4px 0;text-align:right;font-size:14px;color:#64748B;">Ongkir</td>
                <td style="padding:4px 0;text-align:right;font-size:14px;color:#334155;">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
              </tr>
              @endif
              @if($order->tax > 0)
              <tr>
                <td colspan="2" style="padding:4px 0;text-align:right;font-size:14px;color:#64748B;">Pajak</td>
                <td style="padding:4px 0;text-align:right;font-size:14px;color:#334155;">Rp {{ number_format($order->tax, 0, ',', '.') }}</td>
              </tr>
              @endif

              <!-- Grand Total -->
              <tr>
                <td colspan="3" style="padding-top:16px;">
                  <table border="0" cellpadding="0" cellspacing="0" width="100%" class="total-box" style="background:linear-gradient(135deg, #1E293B 0%, #334155 100%);border-radius:12px;">
                    <tr>
                      <td style="padding:18px 20px;">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                          <tr>
                            <td class="total-label" style="font-size:12px;font-weight:500;color:#94A3B8;">Total Pembayaran</td>
                            <td align="right" class="total-amount" style="font-family:'Plus Jakarta Sans',Helvetica,Arial,sans-serif;font-size:20px;font-weight:700;color:#C9A84C;white-space:nowrap;">
                              Rp {{ number_format($order->total, 0, ',', '.') }}
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

            <!-- Shipping Address -->
            @if($order->shippingAddress)
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#F8FAFC;border-radius:16px;margin-bottom:28px;border:1px solid #F1F5F9;">
              <tr>
                <td style="padding:20px 24px;">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="vertical-align:top;padding-right:14px;padding-top:2px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                          <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                      </td>
                      <td>
                        <p class="text-muted" style="margin:0 0 6px;font-size:11px;font-weight:600;color:#64748B;letter-spacing:0.02em;text-transform:uppercase;">Alamat Pengiriman</p>
                        <p style="margin:0 0 3px;font-size:15px;font-weight:700;color:#0F172A;">{{ $order->shippingAddress->name }}</p>
                        <p class="text-muted" style="margin:0;font-size:14px;color:#64748B;line-height:1.6;">
                          {{ $order->shippingAddress->phone }}<br>
                          {{ $order->shippingAddress->address }}<br>
                          {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->province }} {{ $order->shippingAddress->postal_code }}
                        </p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
            @endif

            <!-- Premium CTA -->
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td align="center" style="padding-top:8px;">
                  <a href="{{ $frontendUrl }}/shop/account/orders/{{ $order->order_number }}" target="_blank" class="premium-btn" style="display:inline-block;padding:16px 32px;background:linear-gradient(135deg, #1E293B 0%, #334155 100%);color:#FFFFFF;font-size:15px;font-weight:600;text-decoration:none;border-radius:12px;letter-spacing:0.02em;">
                    <table border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td style="vertical-align:middle;">Lihat Detail Pesanan</td>
                        <td style="padding-left:10px;vertical-align:middle;">
                          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 12h14M12 5l7 7-7 7" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                          </svg>
                        </td>
                      </tr>
                    </table>
                  </a>
                </td>
              </tr>
            </table>

          </td>
        </tr>

        <!-- ══ PREMIUM FOOTER ══ -->
        <tr>
          <td class="footer-bg footer-pad" style="background-color:#F8FAFC;padding:28px 40px;border-top:1px solid #F1F5F9;text-align:center;">
            <p class="text-muted" style="margin:0 0 4px;font-size:13px;color:#64748B;">
              &copy; {{ date('Y') }} Mitologi Clothing
            </p>
            <p class="text-subtle" style="margin:0;font-size:12px;color:#94A3B8;line-height:1.5;">
              Email otomatis, mohon tidak membalas langsung.
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>
