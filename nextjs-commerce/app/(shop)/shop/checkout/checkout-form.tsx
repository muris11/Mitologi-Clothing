"use client";

import Price from "components/shared/ui/price";
import Cookies from "js-cookie";
import {
  ApiError,
  Cart,
  CheckoutResponse,
  MidtransPaymentResponse,
  User,
  Address,
  createCheckout,
} from "lib/api";
import { useToast } from "components/ui/ultra-quality-toast";
import Image from "next/image";
import { useRouter } from "next/navigation";
import Script from "next/script";
import { useState, useEffect } from "react";
import {
  MapPinIcon,
  PlusIcon,
  CheckCircleIcon,
} from "@heroicons/react/24/outline";

declare global {
  interface Window {
    snap: import("lib/api/types").MidtransSnap;
  }
}

export default function CheckoutForm({
  user,
  cart,
}: {
  user: User;
  cart: Cart;
}) {
  const router = useRouter();
  const { addToast } = useToast();
  const [isProcessing, setIsProcessing] = useState(false);
  const [selectedAddressId, setSelectedAddressId] = useState<number | null>(
    null,
  );
  const [addresses, setAddresses] = useState<Address[]>(user.addresses || []);

  // Set default selected address (primary or first)
  useEffect(() => {
    if (addresses.length > 0) {
      const primary = addresses.find((a) => a.isPrimary);
      setSelectedAddressId(primary?.id || addresses[0]?.id || null);
    }
  }, [addresses]);

  const selectedAddress = addresses.find((a) => a.id === selectedAddressId);

  const handleCheckout = async (e: React.FormEvent) => {
    e.preventDefault();

    if (!selectedAddress) {
      addToast({
        title: "Alamat Belum Dipilih",
        description:
          "Silakan pilih alamat pengiriman atau tambah alamat baru di halaman Akun.",
        variant: "warning",
      });
      return;
    }

    setIsProcessing(true);

    try {
      const payload = {
        shipping_name: selectedAddress.recipientName,
        shipping_phone: selectedAddress.phone,
        shipping_address:
          selectedAddress.addressLine1 +
          (selectedAddress.addressLine2
            ? `, ${selectedAddress.addressLine2}`
            : ""),
        shipping_city: selectedAddress.city,
        shipping_province: selectedAddress.province,
        shipping_postal_code: selectedAddress.postalCode,
        notes: "",
      };

      const data = await createCheckout(payload);

      const snapToken = data.snapToken || "";
      const orderNumber = data.orderNumber || "";
      const redirectUrl = data.redirectUrl;

      const isMock = data?.mock === true || snapToken === "MOCK_SNAP_TOKEN";
      if (isMock) {
        addToast({
          title: "Pesanan Berhasil",
          description:
            "Pesanan berhasil dibuat! Mengalihkan ke halaman sukses...",
          variant: "success",
        });
        Cookies.remove("cartSessionId");
        window.dispatchEvent(new Event("auth:changed"));
        router.push(
          "/shop/checkout/success?orderId=" + orderNumber + "&mock=true",
        );
        return;
      }

      if (!snapToken) {
        if (data?.error) {
          throw new Error(data.error);
        }

        if (redirectUrl) {
          router.push(redirectUrl);
          return;
        }

        throw new Error("No payment token returned");
      }

      if (window.snap) {
        window.snap.pay(snapToken, {
          onSuccess: async function (result: MidtransPaymentResponse) {
            try {
              const { confirmOrderPayment } = await import("lib/api");
              await confirmOrderPayment(orderNumber);
            } catch (e) {
              // Silent fail
            }
            Cookies.remove("cartSessionId");
            window.dispatchEvent(new Event("auth:changed"));
            addToast({
              title: "Pembayaran Berhasil",
              description: "Pembayaran berhasil! Pesanan Anda sedang diproses.",
              variant: "success",
            });
            router.push("/shop/checkout/success?orderId=" + orderNumber);
          },
          onPending: async function (result: MidtransPaymentResponse) {
            try {
              const { confirmOrderPayment } = await import("lib/api");
              await confirmOrderPayment(orderNumber);
            } catch (e) {}
            Cookies.remove("cartSessionId");
            window.dispatchEvent(new Event("auth:changed"));
            addToast({
              title: "Pembayaran Tertunda",
              description:
                "Menunggu pembayaran. Silahkan selesaikan pembayaran pesanan Anda.",
              variant: "warning",
            });
            router.push(`/shop/account/orders/${orderNumber}`);
          },
          onError: function (result: MidtransPaymentResponse) {
            Cookies.remove("cartSessionId");
            window.dispatchEvent(new Event("auth:changed"));
            addToast({
              title: "Pembayaran Gagal",
              description:
                "Pembayaran gagal. Silahkan coba lagi dari detail pesanan.",
              variant: "error",
            });
            setIsProcessing(false);
            router.push(`/shop/account/orders/${orderNumber}`);
          },
          onClose: function () {
            Cookies.remove("cartSessionId");
            window.dispatchEvent(new Event("auth:changed"));
            addToast({
              title: "Pembayaran Dibatalkan",
              description:
                "Anda menutup jendela pembayaran. Silahkan lakukan pembayaran dari detail pesanan.",
              variant: "warning",
            });
            setIsProcessing(false);
            router.push(`/shop/account/orders/${orderNumber}`);
          },
        });
      }
    } catch (error: unknown) {
      if (
        error instanceof ApiError &&
        error.isValidationError() &&
        error.errors
      ) {
        addToast({
          title: "Data Tidak Valid",
          description: Object.values(error.errors).flat().join(". "),
          variant: "error",
        });
      } else if (error instanceof Error) {
        addToast({
          title: "Kesalahan",
          description:
            error.message || "Terjadi kesalahan saat memproses checkout.",
          variant: "error",
        });
      } else {
        addToast({
          title: "Kesalahan",
          description: "Terjadi kesalahan saat memproses checkout.",
          variant: "error",
        });
      }

      setIsProcessing(false);
    }
  };

  return (
    <>
      <Script
        src={
          process.env.NEXT_PUBLIC_MIDTRANS_SNAP_URL ||
          "https://app.sandbox.midtrans.com/snap/snap.js"
        }
        data-client-key={process.env.NEXT_PUBLIC_MIDTRANS_CLIENT_KEY}
        strategy="lazyOnload"
      />

      <div className="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start">
        {/* Checkout Form */}
        <section className="lg:col-span-7">
          <form onSubmit={handleCheckout} className="space-y-8">
            {/* Address Selection Section */}
            <div className="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm">
              <div className="flex items-center justify-between mb-8 pb-4 border-b border-slate-100">
                <div className="flex items-center gap-4">
                  <span className="flex items-center justify-center w-8 h-8 rounded-full bg-mitologi-navy text-white font-sans text-sm font-bold shadow-sm">
                    1
                  </span>
                  <h2 className="text-xl font-sans font-extrabold text-mitologi-navy">
                    Pilih Alamat Pengiriman
                  </h2>
                </div>
                <button
                  type="button"
                  onClick={() => router.push("/shop/account")}
                  className="text-sm font-sans font-bold text-mitologi-navy hover:text-mitologi-gold transition-colors flex items-center gap-2"
                >
                  <PlusIcon className="w-4 h-4" />
                  Tambah Alamat
                </button>
              </div>

              {addresses.length === 0 ? (
                <div className="text-center py-12 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                  <MapPinIcon className="w-12 h-12 text-slate-400 mx-auto mb-4" />
                  <p className="text-slate-600 font-sans mb-4">
                    Anda belum memiliki alamat pengiriman
                  </p>
                  <button
                    type="button"
                    onClick={() => router.push("/shop/account")}
                    className="bg-mitologi-navy text-white px-6 py-3 rounded-full font-sans font-bold hover:bg-mitologi-navy/90 transition-colors"
                  >
                    Tambah Alamat Baru
                  </button>
                </div>
              ) : (
                <div className="space-y-4">
                  {addresses.map((address) => (
                    <label
                      key={address.id}
                      className={`relative flex items-start p-5 rounded-2xl border-2 cursor-pointer transition-all ${
                        selectedAddressId === address.id
                          ? "border-mitologi-navy bg-mitologi-navy/5"
                          : "border-slate-200 hover:border-slate-300"
                      }`}
                    >
                      <input
                        type="radio"
                        name="address"
                        value={address.id}
                        checked={selectedAddressId === address.id}
                        onChange={() => setSelectedAddressId(address.id)}
                        className="sr-only"
                      />
                      <div className="flex-1">
                        <div className="flex items-center justify-between mb-2">
                          <div className="flex items-center gap-2">
                            <span className="font-sans font-bold text-mitologi-navy">
                              {address.label}
                            </span>
                            {address.isPrimary && (
                              <span className="text-xs font-sans font-bold bg-mitologi-gold text-mitologi-navy px-2 py-1 rounded-full">
                                Utama
                              </span>
                            )}
                            {selectedAddressId === address.id && (
                              <CheckCircleIcon className="w-5 h-5 text-mitologi-navy" />
                            )}
                          </div>
                        </div>
                        <p className="font-sans font-bold text-slate-900 mb-1">
                          {address.recipientName}
                        </p>
                        <p className="font-sans text-sm text-slate-600 mb-1">
                          {address.phone}
                        </p>
                        <p className="font-sans text-sm text-slate-600">
                          {address.addressLine1}
                          {address.addressLine2 && `, ${address.addressLine2}`}
                        </p>
                        <p className="font-sans text-sm text-slate-600">
                          {address.city}, {address.province}{" "}
                          {address.postalCode}
                        </p>
                      </div>
                    </label>
                  ))}
                </div>
              )}

              {/* Selected Address Summary */}
              {selectedAddress && (
                <div className="mt-6 p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                  <p className="text-sm font-sans font-bold text-emerald-700 mb-2">
                    Alamat yang dipilih:
                  </p>
                  <p className="text-sm font-sans text-emerald-600">
                    {selectedAddress.recipientName} • {selectedAddress.phone}
                  </p>
                  <p className="text-sm font-sans text-emerald-600">
                    {selectedAddress.addressLine1}, {selectedAddress.city}
                  </p>
                </div>
              )}
            </div>

            {/* Submit Button */}
            <button
              type="submit"
              disabled={isProcessing || !selectedAddress}
              className={`w-full bg-mitologi-navy border border-transparent rounded-full py-4 px-6 font-sans tracking-wide font-bold text-white transition-all disabled:bg-slate-300 disabled:text-slate-500 disabled:cursor-not-allowed ${
                isProcessing
                  ? "pulse text-transparent relative shadow-none"
                  : "hover:bg-mitologi-navy/90 focus:outline-none focus:ring-4 focus:ring-mitologi-navy/30 shadow-lg shadow-mitologi-navy/20 transform hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98]"
              }`}
            >
              {isProcessing && (
                <div className="absolute inset-x-0 inset-y-0 flex items-center justify-center">
                  <span className="w-5 h-5 border-2 border-white/20 border-t-white rounded-full animate-spin"></span>
                </div>
              )}
              {isProcessing ? "Memproses..." : "Lanjut ke Pembayaran"}
            </button>
          </form>
        </section>

        {/* Order Summary */}
        <section className="lg:col-span-5 mt-14 lg:mt-0">
          <div className="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm flex flex-col h-full sticky top-24">
            <h2 className="text-xl font-sans font-extrabold text-mitologi-navy mb-8 pb-4 border-b border-slate-100">
              Ringkasan Pesanan
            </h2>
            <ul className="divide-y divide-slate-100 pr-2 custom-scrollbar max-h-96 overflow-y-auto">
              {cart.lines.map((item) => {
                const product = item.merchandise
                  .product as typeof item.merchandise.product & {
                  featured_image?: { url?: string; alt_text?: string };
                  images?: Array<{ url?: string }>;
                };
                const imageUrl =
                  product.featuredImage?.url ||
                  product.featured_image?.url ||
                  product.images?.[0]?.url;
                const altText =
                  product.featuredImage?.altText ||
                  product.featured_image?.alt_text ||
                  product.title;

                return (
                  <li key={item.id} className="py-5 flex gap-5">
                    <div className="shrink-0 relative">
                      <div className="h-20 w-20 border border-slate-100 rounded-xl overflow-hidden bg-slate-50 shadow-sm relative">
                        {imageUrl ? (
                          <Image
                            src={imageUrl}
                            alt={altText}
                            fill
                            className="object-cover object-center"
                            sizes="80px"
                            unoptimized
                          />
                        ) : (
                          <div className="w-full h-full flex items-center justify-center bg-slate-50 text-slate-400 font-sans text-[10px] tracking-widest font-bold text-center p-2 leading-tight">
                            No Img
                          </div>
                        )}
                      </div>
                      <span className="absolute -top-2 -right-2 bg-mitologi-navy text-white rounded-full border border-white h-6 w-6 flex items-center justify-center font-sans text-[11px] font-bold shadow-sm">
                        {item.quantity}
                      </span>
                    </div>
                    <div className="flex-1 flex flex-col justify-center px-1">
                      <span className="text-sm font-bold font-sans text-slate-900 line-clamp-2">
                        {item.merchandise.product.title}
                      </span>
                      {item.merchandise.title !== "Default Title" && (
                        <span className="text-xs font-sans font-medium text-slate-500 mt-1.5 bg-slate-100 inline-flex px-2 py-0.5 rounded-md w-fit">
                          {item.merchandise.title}
                        </span>
                      )}
                    </div>
                    <div className="text-sm font-extrabold font-sans text-mitologi-navy flex flex-col justify-center">
                      <Price
                        amount={item.cost.totalAmount.amount}
                        currencyCode={item.cost.totalAmount.currencyCode}
                      />
                    </div>
                  </li>
                );
              })}
            </ul>
            <dl className="border-t border-slate-100 py-6 space-y-4 mt-2">
              <div className="flex items-center justify-between">
                <dt className="text-sm font-sans font-medium text-slate-500">
                  Subtotal
                </dt>
                <dd className="text-sm font-sans font-bold text-slate-700">
                  <Price
                    amount={cart.cost.subtotalAmount.amount}
                    currencyCode={cart.cost.subtotalAmount.currencyCode}
                  />
                </dd>
              </div>
              <div className="flex items-center justify-between">
                <dt className="text-sm font-sans font-medium text-slate-500">
                  Pengiriman
                </dt>
                <dd className="text-sm font-sans font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">
                  Gratis Ongkir
                </dd>
              </div>
              <div className="flex items-center justify-between border-t border-dashed border-slate-200 mt-6 pt-6 -mx-6 md:-mx-8 px-6 md:px-8 bg-slate-50/50 pb-2 -mb-2 rounded-b-3xl">
                <dt className="text-base font-sans font-bold text-slate-700">
                  Total Bayar
                </dt>
                <dd className="text-xl font-sans font-extrabold text-mitologi-navy">
                  <Price
                    amount={cart.cost.totalAmount.amount}
                    currencyCode={cart.cost.totalAmount.currencyCode}
                  />
                </dd>
              </div>
            </dl>
          </div>
        </section>
      </div>
    </>
  );
}
