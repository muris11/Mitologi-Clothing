"use client";

import { ArrowLeftIcon, CreditCardIcon, MapPinIcon, TruckIcon } from "@heroicons/react/24/outline";
import { Button } from "components/ui/button";
import { getOrderDetail, payOrder } from "lib/api";
import { Address, MidtransPaymentResponse, MidtransSnap, Order, UnknownError } from "lib/api/types";
import Image from "next/image";
import Link from "next/link";
import { notFound, useRouter } from "next/navigation";
import Script from "next/script";
import { use, useEffect, useState } from "react";
import { useToast } from "components/ui/ultra-quality-toast";

export default function OrderDetailPage(props: {
  params: Promise<{ orderNumber: string }>;
}) {
  const params = use(props.params);
  const router = useRouter();
  const { addToast } = useToast();
  const [order, setOrder] = useState<Order | null>(null);
  const [loading, setLoading] = useState(true);
  const [isPaying, setIsPaying] = useState(false);
  const [isRefunding, setIsRefunding] = useState(false);
  const [showRefundForm, setShowRefundForm] = useState(false);
  const [refundReason, setRefundReason] = useState("");

  useEffect(() => {
    getOrderDetail(params.orderNumber).then((data) => {
      setOrder(data || null);
      setLoading(false);
    });
  }, [params.orderNumber]);

  if (loading) {
    return (
        <div className="min-h-screen bg-slate-50 flex items-center justify-center">
            <div className="h-10 w-10 border-4 border-slate-800 border-t-slate-300 rounded-full animate-spin"></div>
        </div>
    );
  }

  if (!order) {
    return notFound();
  }

  const handlePayNow = async () => {
      if (!order) return;
      setIsPaying(true);
      try {
          const res = await payOrder(order.orderNumber);
          if (res && res.snapToken) {
              if (res.mock) {
                  addToast({ variant: "success", title: "Pembayaran disimulasikan sukses (Mock)" });
              } else {
                  const snap = (window as Window & { snap?: MidtransSnap }).snap;
                  if (!snap) {
                      addToast({ variant: "error", title: "Library Snap belum dimuat. Silakan refresh halaman." });
                      return;
                  }
                  snap.pay(res.snapToken, {
                      onSuccess: async function (_result: MidtransPaymentResponse) {
                          try {
                              const { confirmOrderPayment } = await import("lib/api");
                              await confirmOrderPayment(order.orderNumber);
                          } catch (e) {}
                          addToast({ variant: "success", title: "Pembayaran berhasil!" });
                          window.location.reload();
                      },
                      onPending: async function (_result: MidtransPaymentResponse) {
                          try {
                              const { confirmOrderPayment } = await import("lib/api");
                              await confirmOrderPayment(order.orderNumber);
                          } catch (e) {}
                          addToast({ variant: "info", title: "Menunggu pembayaran Anda!" });
                          window.location.reload();
                      },
                      onError: function (_result: MidtransPaymentResponse) {
                          addToast({ variant: "error", title: "Pembayaran gagal!" });
                      },
                      onClose: function () {
                          addToast({ variant: "error", title: "Anda menutup popup sebelum menyelesaikan pembayaran" });
                      }
                  });
              }
          } else {
            addToast({ variant: "error", title: "Gagal mendapatkan token pembayaran" });
          }
      } catch (error: unknown) {
          const err = error as UnknownError;
          addToast({ variant: "error", title: err?.message || "Terjadi kesalahan saat memulai pembayaran" });
      } finally {
          setIsPaying(false);
      }
  };

  const handleRefund = () => {
      setShowRefundForm(true);
  };

  const submitRefund = async () => {
      if (!order || !refundReason.trim()) return;

      setIsRefunding(true);
      try {
          const { requestOrderRefund } = await import("lib/api");
          const res = await requestOrderRefund(order.orderNumber, refundReason.trim());

          if (res.success) {
              addToast({ variant: "success", title: "Pengajuan refund berhasil dikirim. Menunggu konfirmasi admin." });
              setShowRefundForm(false);
              setRefundReason("");
              window.location.reload();
          } else {
              addToast({ variant: "error", title: res.message || "Gagal mengajukan refund." });
          }
      } catch (error: unknown) {
          const err = error as UnknownError;
          addToast({ variant: "error", title: "Terjadi kesalahan sistem saat mengajukan refund." });
      } finally {
          setIsRefunding(false);
      }
  };

  const shippingAddress = order.shippingAddress as Address | undefined;
  const lines = order.items || [];

  return (
    <div className="min-h-screen bg-slate-50 pb-20">
      {/* Midtrans Snap Script */}
      <Script
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key={process.env.NEXT_PUBLIC_MIDTRANS_CLIENT_KEY || ''}
        strategy="lazyOnload"
      />

      {/* Header Background */}
      <div className="bg-mitologi-navy text-white pt-16 pb-32 relative overflow-hidden">
        {/* Decorative elements */}
        <div className="absolute top-0 right-0 w-96 h-96 bg-mitologi-gold/10 rounded-full blur-[100px] pointer-events-none translate-x-1/3 -translate-y-1/3 hidden md:block" />
        <div className="absolute bottom-0 left-0 w-80 h-80 bg-slate-800/50 rounded-full blur-[80px] pointer-events-none -translate-x-1/3 translate-y-1/3 hidden md:block" />
        
        <div className="relative mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 z-10">
            <Link
            href="/shop/account"
            className="text-sm font-sans font-medium text-slate-300 hover:text-mitologi-gold mb-8 inline-flex items-center gap-2 transition-colors group"
            >
            <ArrowLeftIcon className="h-4 w-4 group-hover:-translate-x-1 transition-transform" />
            Kembali ke Akun
            </Link>
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 className="text-3xl md:text-4xl font-sans font-extrabold tracking-tight text-white mb-2">
                        Order #{order.orderNumber}
                    </h1>
                    <p className="text-slate-300 font-sans text-sm font-medium">
                        Dipesan pada {new Date(order.createdAt).toLocaleDateString("id-ID", { day: 'numeric', month: 'long', year: 'numeric' })}
                    </p>
                </div>
                <span
                    className={`inline-flex items-center px-4 py-1.5 rounded-full border text-sm font-sans font-bold shadow-sm ${
                      order.status === "paid" ? "bg-emerald-50 text-emerald-700 border-emerald-200" :
                      order.status === "processing" ? "bg-blue-50 text-blue-700 border-blue-200" :
                      order.status === "shipped" ? "bg-purple-50 text-purple-700 border-purple-200" :
                      order.status === "delivered" ? "bg-teal-50 text-teal-700 border-teal-200" :
                      order.status === "completed" ? "bg-green-50 text-green-700 border-green-200" :
                      order.status === "pending" ? "bg-amber-50 text-amber-700 border-amber-200" :
                      order.status === "refunded" ? "bg-slate-50 text-slate-700 border-slate-200" :
                      "bg-white/10 text-white border-white/20"
                    }`}
                >
                    {
                      order.status === "paid" ? "Lunas" : 
                      order.status === "processing" ? "Diproses" :
                      order.status === "shipped" ? "Dikirim" :
                      order.status === "delivered" ? "Terkirim" :
                      order.status === "completed" ? "Selesai" :
                      order.status === "pending" ? "Menunggu Pembayaran" : 
                      order.status === "refunded" ? "Dikembalikan" :
                      order.status === "cancelled" ? "Dibatalkan" : order.status
                    }
                </span>
            </div>
        </div>
      </div>

      <div className="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 -mt-20 relative z-20 pb-12">
        {/* Product Items */}
        <div className="bg-white rounded-3xl shadow-xl shadow-mitologi-navy/5 border border-slate-100 overflow-hidden mb-8">
            <div className="px-6 md:px-8 py-5 border-b border-slate-100 bg-slate-50/50">
                <h2 className="font-sans font-extrabold text-mitologi-navy flex items-center gap-3 text-lg">
                    <div className="w-10 h-10 rounded-xl bg-white shadow-sm border border-slate-100 flex items-center justify-center">
                        <TruckIcon className="h-5 w-5 text-mitologi-gold" />
                    </div>
                    Rincian Produk
                </h2>
            </div>
            <ul className="divide-y divide-slate-100">
            {lines.map((line) => (
                <li key={line.id} className="p-6 md:px-8 flex flex-col sm:flex-row sm:items-center gap-6 hover:bg-slate-50/50 transition-colors">
                <div className="h-24 w-24 flex-shrink-0 overflow-hidden rounded-2xl border border-slate-100 relative bg-slate-50 shadow-sm">
                    {line.product_image ? (
                    <Image
                        src={line.product_image}
                        alt={line.product_title}
                        fill
                        className="object-cover object-center"
                        unoptimized
                    />
                    ) : (
                    <div className="h-full w-full flex items-center justify-center text-slate-400 text-xs font-sans font-medium">
                        No Img
                    </div>
                    )}
                </div>
                <div className="flex-1 flex flex-col justify-between h-full">
                    <div>
                        <h3 className="font-sans font-bold text-slate-900 text-lg mb-1 line-clamp-2">
                            {line.product_handle ? (
                            <Link
                                href={`/shop/product/${line.product_handle}`}
                                className="hover:text-mitologi-navy transition-colors"
                            >
                                {line.product_title}
                            </Link>
                            ) : (
                            <span>{line.product_title}</span>
                            )}
                        </h3>
                        {line.variant_title && (
                            <p className="text-sm font-sans font-semibold text-slate-500 bg-slate-100 inline-block px-2.5 py-1 rounded-md mb-2">
                                {line.variant_title}
                            </p>
                        )}
                    </div>
                    <div className="flex items-center justify-between mt-3 sm:mt-0">
                         <p className="text-sm font-sans font-medium text-slate-500">Qty: {line.quantity}</p>
                         <p className="font-sans font-bold text-mitologi-navy">
                            {new Intl.NumberFormat("id-ID", {
                            style: "currency",
                            currency: "IDR",
                            minimumFractionDigits: 0,
                            }).format(Number(line.total))}
                        </p>
                    </div>
                </div>
                </li>
            ))}
            </ul>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
            {/* Shipping Info */}
            <div className="bg-white rounded-3xl shadow-xl shadow-mitologi-navy/5 border border-slate-100 p-6 md:p-8 flex flex-col h-full">
                 <div className="flex items-center gap-3 mb-6 pb-6 border-b border-slate-100">
                    <div className="w-10 h-10 rounded-xl bg-slate-50 shadow-sm border border-slate-100 flex items-center justify-center">
                        <MapPinIcon className="h-5 w-5 text-mitologi-navy" />
                    </div>
                    <h3 className="font-sans font-extrabold text-mitologi-navy text-lg">Alamat Pengiriman</h3>
                </div>
                <div className="text-sm font-sans text-slate-500 space-y-1.5 flex-grow">
                    {shippingAddress ? (
                    <>
                        <p className="font-bold text-slate-900 text-base mb-3">{shippingAddress.recipientName}</p>
                        <p>{shippingAddress.addressLine1}</p>
                        {shippingAddress.addressLine2 && (
                        <p>{shippingAddress.addressLine2}</p>
                        )}
                        <p>
                        {shippingAddress.city}, {shippingAddress.province},{" "}
                        {shippingAddress.postalCode}
                        </p>
                        <p>{shippingAddress.country}</p>
                        <div className="mt-4 pt-4 border-t border-slate-100">
                             <p className="font-medium">Telp: <span className="text-slate-900 font-bold">{shippingAddress.phone}</span></p>
                        </div>
                    </>
                    ) : (
                    <p className="text-slate-400 italic">Alamat tidak tersedia.</p>
                    )}
                </div>
            </div>

            {/* Payment Summary */}
            <div className="bg-white rounded-3xl shadow-xl shadow-mitologi-navy/5 border border-slate-100 p-6 md:p-8 h-full flex flex-col">
                <div className="flex items-center gap-3 mb-6 pb-6 border-b border-slate-100">
                    <div className="w-10 h-10 rounded-xl bg-slate-50 shadow-sm border border-slate-100 flex items-center justify-center">
                        <CreditCardIcon className="h-5 w-5 text-mitologi-navy" />
                    </div>
                    <h3 className="font-sans font-extrabold text-mitologi-navy text-lg">Ringkasan Pembayaran</h3>
                </div>
                <div className="space-y-4 text-sm font-sans flex-grow">
                    <div className="flex justify-between items-center">
                        <span className="text-slate-500 font-medium">Subtotal</span>
                        <span className="font-bold text-slate-700">
                            {new Intl.NumberFormat("id-ID", {
                            style: "currency",
                            currency: "IDR",
                            minimumFractionDigits: 0,
                            }).format(order.subtotal || 0)}
                        </span>
                    </div>
                    <div className="flex justify-between items-center">
                        <span className="text-slate-500 font-medium">Pengiriman</span>
                        <span className="font-bold text-slate-700">
                            {new Intl.NumberFormat("id-ID", {
                            style: "currency",
                            currency: "IDR",
                            minimumFractionDigits: 0,
                            }).format(order.shippingCost || 0)}
                        </span>
                    </div>
                    <div className="border-t border-dashed border-slate-200 mt-4 pt-4 flex justify-between items-center bg-slate-50 -mx-6 md:-mx-8 px-6 md:px-8 pb-4 -mb-4 rounded-b-3xl">
                        <span className="text-base font-bold text-slate-700">Total</span>
                        <span className="text-xl font-extrabold text-mitologi-navy">
                            {new Intl.NumberFormat("id-ID", {
                            style: "currency",
                            currency: "IDR",
                            minimumFractionDigits: 0,
                            }).format(order.total)}
                        </span>
                    </div>
                </div>
                {order.status === "pending" && (
                    <div className="mt-8 pt-6 border-t border-slate-100">
                        <Button
                            onClick={handlePayNow}
                            disabled={isPaying}
                            variant="primary"
                            className="w-full flex items-center justify-center font-sans tracking-wide py-3.5 rounded-full shadow-lg shadow-mitologi-navy/20"
                        >
                            {isPaying ? "Memproses..." : "Bayar Sekarang"}
                        </Button>
                    </div>
                )}
                {order.refundRequestedAt && order.status === "processing" && (
                    <div className="mt-6 pt-6 border-t border-slate-100">
                        <div className="bg-amber-50 border border-amber-200 rounded-2xl p-5 flex items-start gap-4 shadow-sm">
                            <div className="bg-amber-100 text-amber-600 rounded-full p-2 shrink-0">
                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <h4 className="text-sm font-bold text-amber-800 font-sans">Pengajuan Refund Sedang Diulas</h4>
                                <p className="text-sm font-medium font-sans text-amber-700 mt-1.5 leading-relaxed">
                                    Pengajuan pengembalian dana dikirim pada {new Date(order.refundRequestedAt).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})} dan sedang menunggu persetujuan admin.
                                </p>
                            </div>
                        </div>
                    </div>
                )}
                {order.status === "processing" && !order.refundRequestedAt && !showRefundForm && (
                    <div className="mt-6 pt-6 border-t border-slate-100 text-center">
                        <Button
                            onClick={handleRefund}
                            variant="secondary"
                            className="w-full flex items-center justify-center gap-2 rounded-xl py-3 border-slate-200 text-slate-700 font-sans shadow-sm"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                <path strokeLinecap="round" strokeLinejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                            </svg>
                            Ajukan Pengembalian Dana
                        </Button>
                        <p className="text-xs font-sans font-medium text-slate-400 mt-3">Akan ditinjau oleh tim admin Mitra</p>
                    </div>
                )}
                {order.status === "processing" && !order.refundRequestedAt && showRefundForm && (
                    <div className="mt-6 pt-6 border-t border-slate-100">
                        <div className="rounded-2xl border border-slate-200 bg-slate-50 p-5 space-y-4 shadow-sm">
                            <label className="block text-sm font-bold font-sans text-slate-700">
                                Alasan Pengajuan Refund
                            </label>
                            <textarea
                                id="refund-reason"
                                value={refundReason}
                                onChange={(e) => setRefundReason(e.target.value)}
                                placeholder="Jelaskan alasan Anda mengajukan pengembalian dana..."
                                rows={3}
                                aria-label="Alasan pengajuan refund"
                                className="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-sans text-slate-700 placeholder:text-slate-400 focus:border-mitologi-navy focus:ring-1 focus:ring-mitologi-navy outline-none transition-shadow resize-none shadow-sm"
                            />
                            <div className="flex gap-3">
                                <Button
                                    onClick={submitRefund}
                                    disabled={isRefunding || !refundReason.trim()}
                                    className="flex-1 py-2.5 rounded-xl font-sans"
                                    variant="primary"
                                >
                                    {isRefunding ? "Mengirim..." : "Kirim Pengajuan"}
                                </Button>
                                <Button
                                    onClick={() => { setShowRefundForm(false); setRefundReason(""); }}
                                    disabled={isRefunding}
                                    variant="secondary"
                                    className="py-2.5 rounded-xl border-slate-200 font-sans"
                                >
                                    Batal
                                </Button>
                            </div>
                            <p className="text-xs font-sans font-medium text-slate-400 text-center mt-2">Pengajuan akan diverifikasi oleh sistem.</p>
                        </div>
                    </div>
                )}
            </div>
        </div>
      </div>
    </div>
  );
}
