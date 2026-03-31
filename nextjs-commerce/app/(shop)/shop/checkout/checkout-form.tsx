"use client";

import Price from "components/shared/ui/price";
import Cookies from "js-cookie";
import { Cart, CheckoutResponse, MidtransPaymentResponse, UnknownError, User } from "lib/api/types";
import { useToast } from "components/ui/ultra-quality-toast";
import Image from "next/image";
import { useRouter } from "next/navigation";
import Script from "next/script";
import { useState } from "react";

declare global {
  interface Window {
    snap: import('lib/api/types').MidtransSnap;
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

  // Find primary address or first available address
  const primaryAddress = user.addresses?.find((a) => a.is_primary) || user.addresses?.[0];

  const [formData, setFormData] = useState({
    firstName: primaryAddress?.recipient_name?.split(" ")[0] || user.name.split(" ")[0] || "",
    lastName: primaryAddress?.recipient_name?.split(" ").slice(1).join(" ") || user.name.split(" ").slice(1).join(" ") || "",
    address: primaryAddress?.address_line_1 || user.address || "",
    city: primaryAddress?.city || user.city || "",
    province: primaryAddress?.province || user.province || "",
    postalCode: primaryAddress?.postal_code || user.postal_code || "",
    phone: primaryAddress?.phone || user.phone || "",
    email: user.email,
  });

  const [errors, setErrors] = useState<{ [key: string]: string }>({});
  const [shakeField, setShakeField] = useState<string | null>(null);

  const validateField = (name: string, value: string) => {
    let error = "";
    if (value.trim() === "" && name !== "lastName") {
      error = "Field ini wajib diisi";
    } else if (name === "phone" && !/^[0-9+]{10,15}$/.test(value)) {
      error = "Nomor telepon tidak valid";
    }
    
    setErrors((prev) => ({ ...prev, [name]: error }));
    return error === "";
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
    // Clear error when user types
    if (errors[name]) {
      setErrors((prev) => ({ ...prev, [name]: "" }));
    }
  };

  const handleBlur = (e: React.FocusEvent<HTMLInputElement>) => {
    validateField(e.target.name, e.target.value);
  };

  const handleCheckout = async (e: React.FormEvent) => {
    e.preventDefault();
    
    // Validate all fields
    let isValid = true;
    let firstErrorField: string | null = null;
    
    Object.keys(formData).forEach((key) => {
      const valid = validateField(key, formData[key as keyof typeof formData]);
      if (!valid) {
        isValid = false;
        if (!firstErrorField) firstErrorField = key;
      }
    });

    if (!isValid) {
      if (firstErrorField) {
        setShakeField(firstErrorField);
        setTimeout(() => setShakeField(null), 600); // 600ms corresponds to the tailwind animation duration
        addToast({
          title: "Data Tidak Lengkap",
          description: "Mohon lengkapi semua data wajib",
          variant: "warning"
        });
      }
      return;
    }

    setIsProcessing(true);

    try {
      const payload = {
        shipping_name: `${formData.firstName} ${formData.lastName}`.trim(),
        shipping_phone: formData.phone,
        shipping_address: formData.address,
        shipping_city: formData.city,
        shipping_province: formData.province || formData.city || "-",
        shipping_postal_code: formData.postalCode,
        notes: "",
      };

      // use apiFetch so Authorization/local token logic is applied
      // apiFetch will throw if response is not ok
      const data: CheckoutResponse = await (
        await import("lib/api")
      ).apiFetch("/checkout", {
        method: "POST",
        body: JSON.stringify(payload),
      });

      // Extract fields from normalized camelCase response
      const snapToken = data.snapToken || '';
      const orderNumber = data.orderNumber || '';
      const redirectUrl = data.redirectUrl;

      // Dev fallback: backend may return a mock token when MIDTRANS keys are not configured.
      const isMock = data?.mock === true || snapToken === "MOCK_SNAP_TOKEN";
      if (isMock) {
        // Simulate a successful payment in development by redirecting to success page.
        addToast({
          title: "Pesanan Berhasil",
          description: "Pesanan berhasil dibuat! Mengalihkan ke halaman sukses...",
          variant: "success"
        });
        // Clear cart — backend already deleted items, frontend needs to sync
        Cookies.remove("cartSessionId");
        window.dispatchEvent(new Event('auth:changed'));
        router.push("/shop/checkout/success?orderId=" + orderNumber + "&mock=true");
        return;
      }

      if (!snapToken) {
        // If server provided a specific error, show it.
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
            // Sync payment status with backend
            try {
              const { confirmOrderPayment } = await import("lib/api");
              await confirmOrderPayment(orderNumber);
            } catch (e) {
              console.error("Failed to confirm payment with backend:", e);
            }
            // Clear cart after successful payment
            Cookies.remove("cartSessionId");
            window.dispatchEvent(new Event('auth:changed'));
            addToast({
              title: "Pembayaran Berhasil",
              description: "Pembayaran berhasil! Pesanan Anda sedang diproses.",
              variant: "success"
            });
            router.push("/shop/checkout/success?orderId=" + orderNumber);
          },
          onPending: async function (result: MidtransPaymentResponse) {
            try {
              const { confirmOrderPayment } = await import("lib/api");
              // This endpoint now mostly just fetches the current order state
              await confirmOrderPayment(orderNumber);
            } catch (e) {}
            // Clear cart after pending payment too — order already created
            Cookies.remove("cartSessionId");
            window.dispatchEvent(new Event('auth:changed'));
            addToast({
              title: "Pembayaran Tertunda",
              description: "Menunggu pembayaran. Silahkan selesaikan pembayaran pesanan Anda.",
              variant: "warning"
            });
            router.push(`/shop/account/orders/${orderNumber}`);
          },
          onError: function (result: MidtransPaymentResponse) {
            // Clear cart, order is already created
            Cookies.remove("cartSessionId");
            window.dispatchEvent(new Event('auth:changed'));
            addToast({
              title: "Pembayaran Gagal",
              description: "Pembayaran gagal. Silahkan coba lagi dari detail pesanan.",
              variant: "error"
            });
            setIsProcessing(false);
            router.push(`/shop/account/orders/${orderNumber}`);
          },
          onClose: function () {
            // Clear cart, order is already created
            Cookies.remove("cartSessionId");
            window.dispatchEvent(new Event('auth:changed'));
            addToast({
              title: "Pembayaran Dibatalkan",
              description: "Anda menutup jendela pembayaran. Silahkan lakukan pembayaran dari detail pesanan.",
              variant: "warning"
            });
            setIsProcessing(false);
            router.push(`/shop/account/orders/${orderNumber}`);
          },
        });
      }
    } catch (error: unknown) {
      const err = error as UnknownError;
      addToast({
        title: "Kesalahan",
        description: err?.message || "Terjadi kesalahan saat memproses checkout.",
        variant: "error"
      });

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
            <div className="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm">
              <h2 className="text-xl font-sans font-extrabold text-mitologi-navy mb-8 pb-4 border-b border-slate-100 flex items-center gap-4">
                <span className="flex items-center justify-center w-8 h-8 rounded-full bg-mitologi-navy text-white font-sans text-sm font-bold shadow-sm">1</span>
                Informasi Pengiriman
              </h2>
              <div className="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-6">
                <div>
                  <label
                    htmlFor="firstName"
                    className="block text-sm font-sans font-bold text-slate-700 mb-2"
                  >
                    Nama Depan
                  </label>
                  <input
                    type="text"
                    id="firstName"
                    name="firstName"
                    required
                    value={formData.firstName}
                    onChange={handleChange}
                    onBlur={handleBlur}
                    autoComplete="given-name"
                    className={`block w-full rounded-xl border bg-slate-50 shadow-sm focus:ring-1 sm:text-sm py-3 font-sans px-4 outline-none transition-shadow ${
                      errors.firstName ? "border-red-500 focus:border-red-500 focus:ring-red-500" : "border-slate-200 focus:border-mitologi-navy focus:ring-mitologi-navy"
                    } ${shakeField === 'firstName' ? 'animate-shake' : ''}`}
                    placeholder="Nama Depan"
                  />
                  {errors.firstName && <span className="text-red-500 text-xs mt-1 block font-sans font-medium">{errors.firstName}</span>}
                </div>
                <div>
                  <label
                    htmlFor="lastName"
                    className="block text-sm font-sans font-bold text-slate-700 mb-2"
                  >
                    Nama Belakang
                  </label>
                  <input
                    type="text"
                    id="lastName"
                    name="lastName"
                    value={formData.lastName}
                    onChange={handleChange}
                    onBlur={handleBlur}
                    autoComplete="family-name"
                    className={`block w-full rounded-xl border bg-slate-50 shadow-sm focus:ring-1 sm:text-sm py-3 font-sans px-4 outline-none transition-shadow ${
                      errors.lastName ? "border-red-500 focus:border-red-500 focus:ring-red-500" : "border-slate-200 focus:border-mitologi-navy focus:ring-mitologi-navy"
                    }`}
                    placeholder="Nama Belakang"
                  />
                </div>
                <div className="sm:col-span-2 mt-4">
                  <label
                    htmlFor="address"
                    className="block text-sm font-sans font-bold text-slate-700 mb-2"
                  >
                    Alamat Lengkap
                  </label>
                  <input
                    type="text"
                    id="address"
                    name="address"
                    required
                    value={formData.address}
                    onChange={handleChange}
                    onBlur={handleBlur}
                    autoComplete="street-address"
                    className={`block w-full rounded-xl border bg-slate-50 shadow-sm focus:ring-1 sm:text-sm py-3 font-sans px-4 outline-none transition-shadow ${
                      errors.address ? "border-red-500 focus:border-red-500 focus:ring-red-500" : "border-slate-200 focus:border-mitologi-navy focus:ring-mitologi-navy"
                    } ${shakeField === 'address' ? 'animate-shake' : ''}`}
                    placeholder="Nama Jalan, No. Rumah, RT/RW"
                  />
                  {errors.address && <span className="text-red-500 text-xs mt-1 block font-sans font-medium">{errors.address}</span>}
                </div>
                <div className="mt-4">
                  <label
                    htmlFor="city"
                    className="block text-sm font-sans font-bold text-slate-700 mb-2"
                  >
                    Kota / Kabupaten
                  </label>
                  <input
                    type="text"
                    id="city"
                    name="city"
                    required
                    value={formData.city}
                    onChange={handleChange}
                    onBlur={handleBlur}
                    autoComplete="address-level2"
                    className={`block w-full rounded-xl border bg-slate-50 shadow-sm focus:ring-1 sm:text-sm py-3 font-sans px-4 outline-none transition-shadow ${
                      errors.city ? "border-red-500 focus:border-red-500 focus:ring-red-500" : "border-slate-200 focus:border-mitologi-navy focus:ring-mitologi-navy"
                    } ${shakeField === 'city' ? 'animate-shake' : ''}`}
                    placeholder="Contoh: Jakarta Selatan"
                  />
                  {errors.city && <span className="text-red-500 text-xs mt-1 block font-sans font-medium">{errors.city}</span>}
                </div>

                <div className="mt-4">
                  <label
                    htmlFor="province"
                    className="block text-sm font-sans font-bold text-slate-700 mb-2"
                  >
                    Provinsi
                  </label>
                  <input
                    type="text"
                    id="province"
                    name="province"
                    required
                    value={formData.province}
                    onChange={handleChange}
                    onBlur={handleBlur}
                    autoComplete="address-level1"
                    className={`block w-full rounded-xl border bg-slate-50 shadow-sm focus:ring-1 sm:text-sm py-3 font-sans px-4 outline-none transition-shadow ${
                      errors.province ? "border-red-500 focus:border-red-500 focus:ring-red-500" : "border-slate-200 focus:border-mitologi-navy focus:ring-mitologi-navy"
                    } ${shakeField === 'province' ? 'animate-shake' : ''}`}
                    placeholder="Contoh: DKI Jakarta"
                  />
                  {errors.province && <span className="text-red-500 text-xs mt-1 block font-sans font-medium">{errors.province}</span>}
                </div>

                <div className="mt-4">
                  <label
                    htmlFor="postalCode"
                    className="block text-sm font-sans font-bold text-slate-700 mb-2"
                  >
                    Kode Pos
                  </label>
                  <input
                    type="text"
                    id="postalCode"
                    name="postalCode"
                    required
                    value={formData.postalCode}
                    onChange={handleChange}
                    onBlur={handleBlur}
                    autoComplete="postal-code"
                    className={`block w-full rounded-xl border bg-slate-50 shadow-sm focus:ring-1 sm:text-sm py-3 font-sans px-4 outline-none transition-shadow ${
                      errors.postalCode ? "border-red-500 focus:border-red-500 focus:ring-red-500" : "border-slate-200 focus:border-mitologi-navy focus:ring-mitologi-navy"
                    } ${shakeField === 'postalCode' ? 'animate-shake' : ''}`}
                    placeholder="12345"
                  />
                  {errors.postalCode && <span className="text-red-500 text-xs mt-1 block font-sans font-medium">{errors.postalCode}</span>}
                </div>
                <div className="sm:col-span-2 mt-4">
                  <label
                    htmlFor="phone"
                    className="block text-sm font-sans font-bold text-slate-700 mb-2"
                  >
                    Nomor Telepon (WhatsApp)
                  </label>
                  <input
                    type="tel"
                    id="phone"
                    name="phone"
                    required
                    value={formData.phone}
                    onChange={handleChange}
                    onBlur={handleBlur}
                    autoComplete="tel"
                    className={`block w-full rounded-xl border bg-slate-50 shadow-sm focus:ring-1 sm:text-sm py-3 font-sans px-4 outline-none transition-shadow ${
                      errors.phone ? "border-red-500 focus:border-red-500 focus:ring-red-500" : "border-slate-200 focus:border-mitologi-navy focus:ring-mitologi-navy"
                    } ${shakeField === 'phone' ? 'animate-shake' : ''}`}
                    placeholder="08123456789"
                  />
                  {errors.phone && <span className="text-red-500 text-xs mt-1 block font-sans font-medium">{errors.phone}</span>}
                </div>
              </div>
            </div>

            <button
              type="submit"
              disabled={isProcessing}
              className={`mt-8 w-full bg-mitologi-navy border border-transparent rounded-full py-4 px-6 font-sans tracking-wide font-bold text-white transition-all disabled:bg-slate-300 disabled:text-slate-500 disabled:cursor-not-allowed ${
                isProcessing 
                  ? 'pulse text-transparent relative shadow-none' 
                  : 'hover:bg-mitologi-navy/90 focus:outline-none focus:ring-4 focus:ring-mitologi-navy/30 shadow-lg shadow-mitologi-navy/20 transform hover:-translate-y-0.5 active:translate-y-0 active:scale-[0.98]'
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
            <ul className="divide-y divide-slate-100 pr-2 custom-scrollbar">
              {cart.lines.map((item) => {
                // Safe access for image URL, handling potential camelCase or snake_case
                const product = item.merchandise.product as typeof item.merchandise.product & {
                  featured_image?: { url?: string; alt_text?: string };
                  images?: Array<{ url?: string }>;
                };
                const imageUrl = product.featuredImage?.url || product.featured_image?.url || product.images?.[0]?.url;
                const altText = product.featuredImage?.altText || product.featured_image?.alt_text || product.title;

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
                          <div className="w-full h-full flex items-center justify-center bg-slate-50 text-slate-400 font-sans text-[10px] tracking-widest font-bold text-center p-2 leading-tight">No Img</div>
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
                      {item.merchandise.title !== 'Default Title' && (
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
                <dt className="text-sm font-sans font-medium text-slate-500">Subtotal</dt>
                <dd className="text-sm font-sans font-bold text-slate-700">
                  <Price
                    amount={cart.cost.subtotalAmount.amount}
                    currencyCode={cart.cost.subtotalAmount.currencyCode}
                  />
                </dd>
              </div>
              <div className="flex items-center justify-between">
                <dt className="text-sm font-sans font-medium text-slate-500">Pengiriman</dt>
                <dd className="text-sm font-sans font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">
                  Gratis Ongkir
                </dd>
              </div>
              <div className="flex items-center justify-between border-t border-dashed border-slate-200 mt-6 pt-6 -mx-6 md:-mx-8 px-6 md:px-8 bg-slate-50/50 pb-2 -mb-2 rounded-b-3xl">
                <dt className="text-base font-sans font-bold text-slate-700">Total Bayar</dt>
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
