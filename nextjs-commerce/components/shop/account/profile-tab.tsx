"use client";

import { CalendarDaysIcon, CreditCardIcon, EnvelopeIcon, MapPinIcon, PencilSquareIcon, PhoneIcon, ShoppingBagIcon, UserCircleIcon } from "@heroicons/react/24/outline";
import Modal from "components/shared/ui/modal";
import { Button } from "components/ui/button";
import { updateAvatar, updateProfile } from "lib/api";
import { UnknownError, User } from "lib/api/types";
import { useAuth } from "lib/hooks/useAuth";
import { useRouter } from "next/navigation";
import { useEffect, useRef, useState } from "react";
import { useToast } from "components/ui/ultra-quality-toast";

interface ProfileTabProps {
  user: User;
  totalOrders: number;
  totalSpend: number;
  userSince: number;
}

export function ProfileTab({ user, totalOrders, totalSpend, userSince }: ProfileTabProps) {
  const router = useRouter();
  const { refreshProfile } = useAuth();
  const { addToast } = useToast();

  const [isProfileModalOpen, setIsProfileModalOpen] = useState(false);
  const [isAddressModalOpen, setIsAddressModalOpen] = useState(false);
  const fileInputRef = useRef<HTMLInputElement>(null);
  const [isUploadingAvatar, setIsUploadingAvatar] = useState(false);
  const [avatarError, setAvatarError] = useState<string | null>(null);
  const [avatarLoadError, setAvatarLoadError] = useState(false);
  const [isUpdating, setIsUpdating] = useState(false);

  useEffect(() => {
    setAvatarLoadError(false);
  }, [user.avatar_url]);

  const [profileForm, setProfileForm] = useState({
      name: user.name || "",
      email: user.email || "",
      phone: user.phone || ""
  });

  const [addressForm, setAddressForm] = useState({
      address: user.address || "", 
      city: user.city || "",
      province: user.province || "",
      postal_code: user.postal_code || ""
  });

  const getReadableError = (error: unknown, fallback: string) => {
    const err = error as UnknownError & {
      errors?: Record<string, string[]>;
      response?: { data?: { errors?: Record<string, string[]>; message?: string; error?: string } };
    };

    const avatarFieldError =
      err?.errors?.avatar?.[0] ??
      err?.response?.data?.errors?.avatar?.[0];

    if (avatarFieldError) return avatarFieldError;

    const genericMessage =
      err?.message ||
      err?.response?.data?.message ||
      err?.response?.data?.error;

    return genericMessage || fallback;
  };

  const handleUpdateProfile = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsUpdating(true);
    try {
        await updateProfile(profileForm);
        await refreshProfile();
        addToast({ variant: "success", title: "Profil berhasil diperbarui" });
        setIsProfileModalOpen(false);
        router.refresh();
    } catch (error: unknown) {
        const err = error as UnknownError;
        addToast({ variant: "error", title: err?.message || "Gagal memperbarui profil" });
    } finally {
        setIsUpdating(false);
    }
  };

  const handleUpdateAddress = async (e: React.FormEvent) => {
      e.preventDefault();
      setIsUpdating(true);
      try {
          await updateProfile(addressForm);
          await refreshProfile();
          addToast({ variant: "success", title: "Alamat berhasil diperbarui" });
          setIsAddressModalOpen(false);
          router.refresh();
      } catch (error: unknown) {
          const err = error as UnknownError;
          addToast({ variant: "error", title: err?.message || "Gagal memperbarui alamat" });
      } finally {
          setIsUpdating(false);
      }
  };

  const handleAvatarChange = async (e: React.ChangeEvent<HTMLInputElement>) => {
      const file = e.target.files?.[0];
      if (!file) return;

      setAvatarError(null);

      if (file.size > 2 * 1024 * 1024) {
          const message = "Ukuran file maksimal 2MB";
          setAvatarError(message);
          addToast({ variant: "error", title: message });
          if (fileInputRef.current) fileInputRef.current.value = "";
          return;
      }

      setIsUploadingAvatar(true);
      try {
          await updateAvatar(file);
          await refreshProfile();
          setAvatarError(null);
          addToast({ variant: "success", title: "Foto profil berhasil diperbarui" });
          router.refresh();
      } catch (error: unknown) {
          const message = getReadableError(error, "Gagal mengunggah foto profil");
          setAvatarError(message);
          addToast({ variant: "error", title: message });
      } finally {
          setIsUploadingAvatar(false);
          if (fileInputRef.current) fileInputRef.current.value = "";
      }
  };

  const statsCards = [
    {
      label: "Total Pesanan",
      value: totalOrders.toString(),
      icon: ShoppingBagIcon,
      gradient: "from-slate-100 to-slate-50",
      iconBg: "bg-white text-slate-500 border border-slate-200",
    },
    {
      label: "Total Belanja",
      value: new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR", maximumFractionDigits: 0 }).format(totalSpend),
      icon: CreditCardIcon,
      gradient: "from-stone-100 to-stone-50",
      iconBg: "bg-white text-slate-500 border border-slate-200",
    },
    {
      label: "Member Sejak",
      value: userSince.toString(),
      icon: CalendarDaysIcon,
      gradient: "from-zinc-100 to-zinc-50",
      iconBg: "bg-white text-slate-500 border border-slate-200",
    },
  ];

  return (
    <div className="space-y-8 max-w-4xl">
        {/* Page Header */}
        <div className="border-b border-slate-100 pb-6">
            <div className="flex items-center gap-3 mb-2">
                <div className="flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 text-slate-500 shrink-0">
                    <UserCircleIcon className="h-5 w-5" />
                </div>
                <div>
                    <p className="text-[11px] font-sans font-bold uppercase tracking-[0.18em] text-slate-400">Profil Akun</p>
                    <h2 className="text-2xl font-sans font-bold text-mitologi-navy">Informasi Pribadi</h2>
                </div>
            </div>
            <p className="text-sm font-sans text-slate-500 ml-13">Kelola profil, alamat pengiriman, dan pantau statistik belanja Anda.</p>
        </div>

        {/* Stats Row */}
        <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {statsCards.map((stat) => {
                const Icon = stat.icon;
                return (
                    <div key={stat.label} className={`p-5 rounded-2xl bg-gradient-to-br ${stat.gradient} border border-slate-200 shadow-sm transition-colors duration-300 hover:border-slate-300`}>
                        <div className="relative z-10">
                            <div className="flex items-center gap-3 mb-3">
                                <div className={`p-2 rounded-xl ${stat.iconBg}`}>
                                    <Icon className="h-4 w-4" />
                                </div>
                                <p className="text-xs font-sans text-slate-500 uppercase tracking-[0.14em] font-bold">{stat.label}</p>
                            </div>
                            <p className="text-2xl font-sans font-extrabold text-mitologi-navy">{stat.value}</p>
                        </div>
                    </div>
                );
            })}
        </div>
        
        {/* Profile Card */}
        <div className="p-6 md:p-8 rounded-3xl bg-white border border-slate-200/80 shadow-sm relative overflow-hidden group hover:shadow-lg hover:border-slate-300/80 transition-all duration-300">
            {/* Decorative gradient */}
            <div className="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-mitologi-gold/[0.02] z-0" />
            <div className="absolute top-0 right-0 w-64 h-64 rounded-full bg-mitologi-gold/5 -mr-32 -mt-32 blur-3xl z-0" />

            <div className="relative z-10 flex flex-col md:flex-row items-start md:items-center gap-6 md:gap-8">
                {/* Avatar */}
                <div className="relative shrink-0">
                  <div className="relative group/avatar cursor-pointer" onClick={() => !isUploadingAvatar && fileInputRef.current?.click()}>
                    <div className="h-28 w-28 rounded-2xl p-1 bg-gradient-to-br from-mitologi-navy/10 to-mitologi-gold/20 shrink-0 relative overflow-hidden shadow-sm group-hover/avatar:shadow-lg transition-all duration-300">
                        <div className="h-full w-full rounded-xl bg-white flex items-center justify-center text-4xl font-sans font-bold text-mitologi-navy overflow-hidden">
                            {user.avatar_url && !avatarLoadError ? (
                                <img
                                  src={user.avatar_url}
                                  alt={user.name || "Foto profil"}
                                  className="w-full h-full object-cover"
                                  onError={() => setAvatarLoadError(true)}
                                />
                            ) : (
                                <span className="bg-gradient-to-br from-mitologi-navy to-mitologi-navy/70 bg-clip-text text-transparent">
                                    {user.name?.charAt(0).toUpperCase() || "U"}
                                </span>
                            )}
                        </div>
                        
                        {/* Hover Overlay */}
                        <div className="absolute inset-1 rounded-xl bg-mitologi-navy/70 flex flex-col items-center justify-center opacity-0 group-hover/avatar:opacity-100 transition-all duration-300 backdrop-blur-sm">
                            {isUploadingAvatar ? (
                                <div className="w-6 h-6 border-2 border-white border-t-transparent rounded-full animate-spin" />
                            ) : (
                                <>
                                    <PencilSquareIcon className="h-5 w-5 text-white mb-1" />
                                    <span className="text-[10px] font-sans font-bold text-white/90">Ubah Foto</span>
                                </>
                            )}
                        </div>
                        
                        <input 
                            type="file" 
                            ref={fileInputRef} 
                            onChange={handleAvatarChange} 
                            accept="image/jpeg,image/png,image/webp,image/jpg" 
                            className="hidden" 
                        />
                    </div>
                  </div>
                  <p className="mt-2 max-w-[170px] text-[11px] leading-relaxed text-slate-500">
                    JPG, PNG, WEBP. Maksimal 2MB.
                  </p>
                  {avatarError && (
                    <p className="mt-1 max-w-[170px] text-[11px] leading-relaxed text-red-600">
                      {avatarError}
                    </p>
                  )}
                </div>

                {/* User Info */}
                <div className="flex-1 w-full min-w-0">
                    <div className="flex items-center gap-3 mb-1.5 flex-wrap">
                        <h3 className="font-sans font-bold text-2xl text-mitologi-navy truncate">{user.name}</h3>
                        <span className="inline-flex items-center text-xs font-sans font-bold px-3 py-1 rounded-full bg-slate-100 text-slate-600 border border-slate-200 whitespace-nowrap">
                            Akun Aktif
                        </span>
                    </div>
                    <p className="text-sm font-sans text-slate-400 mb-4">Bergabung sejak {userSince}</p>
                    
                    <div className="flex flex-wrap gap-3 text-sm font-sans font-medium text-slate-600">
                        <div className="flex items-center gap-2.5 bg-slate-50/80 py-2 px-4 rounded-xl border border-slate-100 max-w-full overflow-hidden hover:bg-slate-100/60 transition-colors">
                            <EnvelopeIcon className="h-4 w-4 text-slate-400 shrink-0" />
                            <span className="truncate">{user.email}</span>
                        </div>
                        <div className="flex items-center gap-2.5 bg-slate-50/80 py-2 px-4 rounded-xl border border-slate-100 hover:bg-slate-100/60 transition-colors">
                            <PhoneIcon className="h-4 w-4 text-slate-400 shrink-0" />
                            <span>{user.phone || "Belum diatur"}</span>
                        </div>
                    </div>
                </div>

                {/* Edit Button */}
                <Button variant="secondary" onClick={() => setIsProfileModalOpen(true)} className="shrink-0 shadow-sm mt-2 md:mt-0 rounded-xl">
                    <PencilSquareIcon className="h-4 w-4 mr-2" />
                    Edit Profil
                </Button>
            </div>
        </div>

        {/* Address Card */}
        <div className="p-6 md:p-8 rounded-3xl border border-slate-200/80 bg-white hover:border-slate-300/80 hover:shadow-lg transition-all duration-300 group relative overflow-hidden">
            <div className="absolute top-0 right-0 w-48 h-48 bg-blue-500/[0.03] rounded-full -mr-24 -mt-24 blur-2xl z-0 group-hover:scale-110 transition-transform duration-500" />
            
            <div className="flex items-center justify-between mb-6 pb-5 border-b border-slate-100 relative z-10">
                <div className="flex items-center gap-4">
                    <div className="p-3 rounded-xl bg-slate-50 text-slate-500 border border-slate-100 group-hover:bg-mitologi-navy group-hover:text-white group-hover:border-mitologi-navy transition-all duration-300 shadow-sm">
                        <MapPinIcon className="h-5 w-5" />
                    </div>
                    <div>
                        <h3 className="font-sans font-bold text-lg text-mitologi-navy">Alamat Pengiriman</h3>
                        <p className="text-xs font-sans text-slate-400 mt-0.5">Alamat utama untuk pengiriman pesanan</p>
                    </div>
                </div>
                {(user.address || user.city) && (
                    <span className="text-xs font-sans font-bold text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-3 py-1 rounded-full flex items-center gap-1.5">
                        <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse" />
                        Aktif
                    </span>
                )}
            </div>

            <div className="pl-0 md:pl-[68px] relative z-10">
                {(user.address || user.city) ? (
                    <div className="text-sm font-sans text-slate-600 space-y-1.5 mb-6 bg-slate-50/50 p-4 rounded-xl border border-slate-100/80">
                        <p className="leading-relaxed font-medium">{user.address}</p>
                        <p className="text-slate-500">{user.city}, {user.province} {user.postal_code}</p>
                    </div>
                ) : (
                    <div className="mb-6 p-5 rounded-xl border border-dashed border-slate-200 bg-slate-50/30 text-center">
                        <MapPinIcon className="h-8 w-8 text-slate-300 mx-auto mb-2" />
                        <p className="text-sm font-sans text-slate-500 leading-relaxed">
                            Belum ada alamat tersimpan.<br />
                            <span className="text-slate-400">Tambahkan untuk memudahkan checkout.</span>
                        </p>
                    </div>
                )}
                
                <Button 
                  onClick={() => setIsAddressModalOpen(true)}
                  variant="secondary"
                  className="text-sm shadow-sm rounded-xl"
                >
                    <PencilSquareIcon className="h-4 w-4 mr-2" />
                    {(user.address || user.city) ? "Ubah Alamat" : "Tambah Alamat Baru"}
                </Button>
            </div>
        </div>

        {/* ——— Modals ——— */}
        <Modal isOpen={isProfileModalOpen} onClose={() => setIsProfileModalOpen(false)} title="Edit Profil">
            <form onSubmit={handleUpdateProfile} className="space-y-5">
                <div>
                    <label className="block text-sm font-sans font-bold text-mitologi-navy mb-2">Nama Lengkap</label>
                    <input 
                        type="text" 
                        className="block w-full rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm focus:border-mitologi-navy focus:ring-2 focus:ring-mitologi-navy/10 sm:text-sm py-3 px-4 font-sans transition-all placeholder:text-slate-400"
                        value={profileForm.name}
                        onChange={(e) => setProfileForm({...profileForm, name: e.target.value})}
                        autoComplete="name"
                        placeholder="Masukkan nama lengkap"
                        required
                    />
                </div>
                <div>
                    <label className="block text-sm font-sans font-bold text-mitologi-navy mb-2">Email</label>
                    <input 
                        type="email" 
                        className="block w-full rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm focus:border-mitologi-navy focus:ring-2 focus:ring-mitologi-navy/10 sm:text-sm py-3 px-4 font-sans transition-all placeholder:text-slate-400"
                        value={profileForm.email}
                        onChange={(e) => setProfileForm({...profileForm, email: e.target.value})}
                        autoComplete="email"
                        placeholder="nama@email.com"
                        required
                    />
                </div>
                <div>
                    <label className="block text-sm font-sans font-bold text-mitologi-navy mb-2">Nomor Telepon</label>
                    <input 
                        type="tel" 
                        className="block w-full rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm focus:border-mitologi-navy focus:ring-2 focus:ring-mitologi-navy/10 sm:text-sm py-3 px-4 font-sans transition-all placeholder:text-slate-400"
                        value={profileForm.phone}
                        autoComplete="tel"
                        placeholder="08xxxxxxxxxx"
                        onChange={(e) => setProfileForm({...profileForm, phone: e.target.value})}
                    />
                </div>
                <div className="pt-4 flex justify-end gap-3 border-t border-slate-100">
                    <Button variant="secondary" type="button" onClick={() => setIsProfileModalOpen(false)} className="shadow-sm rounded-xl">Batal</Button>
                    <Button type="submit" disabled={isUpdating} variant="primary" className="shadow-md rounded-xl">
                        {isUpdating ? "Menyimpan…" : "Simpan Perubahan"}
                    </Button>
                </div>
            </form>
        </Modal>

        <Modal isOpen={isAddressModalOpen} onClose={() => setIsAddressModalOpen(false)} title="Alamat Pengiriman">
            <form onSubmit={handleUpdateAddress} className="space-y-5">
                <div>
                    <label className="block text-sm font-sans font-bold text-mitologi-navy mb-2">Alamat Lengkap</label>
                    <input 
                        type="text" 
                        className="block w-full rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm focus:border-mitologi-navy focus:ring-2 focus:ring-mitologi-navy/10 sm:text-sm py-3 px-4 font-sans transition-all placeholder:text-slate-400"
                        value={addressForm.address}
                        autoComplete="street-address"
                        placeholder="Jl. Contoh No. 123, RT/RW"
                        onChange={(e) => setAddressForm({...addressForm, address: e.target.value})}
                    />
                </div>
                <div className="grid grid-cols-2 gap-4">
                    <div>
                        <label className="block text-sm font-sans font-bold text-mitologi-navy mb-2">Kota</label>
                        <input 
                            type="text" 
                            className="block w-full rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm focus:border-mitologi-navy focus:ring-2 focus:ring-mitologi-navy/10 sm:text-sm py-3 px-4 font-sans transition-all placeholder:text-slate-400"
                            value={addressForm.city}
                            autoComplete="address-level2"
                            placeholder="Jakarta"
                            onChange={(e) => setAddressForm({...addressForm, city: e.target.value})}
                        />
                    </div>
                    <div>
                        <label className="block text-sm font-sans font-bold text-mitologi-navy mb-2">Provinsi</label>
                        <input 
                            type="text" 
                            className="block w-full rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm focus:border-mitologi-navy focus:ring-2 focus:ring-mitologi-navy/10 sm:text-sm py-3 px-4 font-sans transition-all placeholder:text-slate-400"
                            value={addressForm.province}
                            autoComplete="address-level1"
                            placeholder="DKI Jakarta"
                            onChange={(e) => setAddressForm({...addressForm, province: e.target.value})}
                        />
                    </div>
                </div>
                <div>
                    <label className="block text-sm font-sans font-bold text-mitologi-navy mb-2">Kode Pos</label>
                    <input 
                        type="text" 
                        className="block w-full rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm focus:border-mitologi-navy focus:ring-2 focus:ring-mitologi-navy/10 sm:text-sm py-3 px-4 font-sans transition-all placeholder:text-slate-400"
                        value={addressForm.postal_code}
                        autoComplete="postal-code"
                        placeholder="12345"
                        onChange={(e) => setAddressForm({...addressForm, postal_code: e.target.value})}
                    />
                </div>
                <div className="pt-4 flex justify-end gap-3 border-t border-slate-100">
                    <Button variant="secondary" type="button" onClick={() => setIsAddressModalOpen(false)} className="shadow-sm rounded-xl">Batal</Button>
                    <Button type="submit" disabled={isUpdating} variant="primary" className="shadow-md rounded-xl">
                        {isUpdating ? "Menyimpan…" : "Simpan Alamat"}
                    </Button>
                </div>
            </form>
        </Modal>
    </div>
  );
}
