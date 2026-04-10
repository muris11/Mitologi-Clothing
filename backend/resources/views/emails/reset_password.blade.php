<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Mitologi Clothing</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        {!! file_exists(public_path('css/email.css')) ? file_get_contents(public_path('css/email.css')) : '' !!}
        body { font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
    </style>
</head>
<body class="bg-[#f1f5f9] text-[#0f172a] m-0 p-0 antialiased" style="font-family: 'Plus Jakarta Sans', 'Inter', Helvetica, Arial, sans-serif;">
    <div class="w-full bg-[#f1f5f9] py-10 px-4 sm:px-6">
        <!-- Main Container -->
        <div class="max-w-xl mx-auto w-full">
            
            <!-- Email Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-[#e2e8f0] overflow-hidden">
                
                <!-- Premium Header -->
                <div class="bg-[#0f172a] py-8 px-6 text-center border-b-[4px] border-[#d4af37]">
                    <h1 class="text-2xl font-black text-[#d4af37] uppercase tracking-[0.2em] m-0">{{ config('app.name', 'Mitologi Clothing') }}</h1>
                </div>

                <!-- Body content -->
                <div class="p-8 sm:p-10">
                    <h2 class="text-xl sm:text-2xl font-bold text-[#0f172a] mb-6">Reset Password Akun</h2>
                    
                    <p class="text-base text-[#334155] leading-relaxed mb-6 font-medium">
                        Halo <span class="text-[#0f172a] font-bold">{{ $name }}</span>,
                    </p>
                    
                    <p class="text-sm text-[#475569] leading-relaxed mb-8">
                        Kami menerima permintaan untuk melakukan pengaturan ulang kata sandi pada akun Mitologi Clothing Anda. Jika Anda merasa <strong>tidak melakukan permintaan ini</strong>, Anda dapat mengabaikan pesan ini dengan aman.
                    </p>

                    <!-- Action Button -->
                    <div class="mb-10 text-center">
                        <a href="{{ $url }}" class="inline-block bg-[#0f172a] text-[#d4af37] border border-[#0f172a] border-b-[4px] border-b-[#020617] rounded-xl font-bold text-sm uppercase tracking-widest py-4 px-10 hover:bg-[#1e293b] hover:translate-y-[2px] transition-all decoration-none" style="text-decoration: none;">
                            Atur Ulang Password
                        </a>
                    </div>

                    <!-- Alert Panel -->
                    <div class="bg-[#fffbeb] border border-[#fef08a] rounded-xl p-5 mb-8 flex gap-3">
                        <div>
                            <p class="text-xs text-[#b45309] font-bold uppercase tracking-wider mb-1">Informasi Keamanan</p>
                            <p class="text-[13px] text-[#92400e] leading-relaxed m-0">
                                Tautan reset password ini bersifat rahasia dan akan kedaluwarsa secara otomatis dalam waktu <strong>60 menit</strong>.
                            </p>
                        </div>
                    </div>

                    <!-- Sign Off -->
                    <div class="border-t border-[#e2e8f0] pt-6 mt-6">
                        <p class="text-sm text-[#475569] leading-relaxed mb-1 font-medium">
                            Salam Hangat,
                        </p>
                        <p class="text-sm text-[#0f172a] font-bold uppercase tracking-wide">
                            Mitologi Clothing Team
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center px-4">
                <p class="text-xs text-[#64748b] mb-2 font-bold uppercase tracking-widest">© {{ date('Y') }} {{ config('app.name', 'Mitologi Clothing') }}</p>
                <p class="text-[11px] text-[#94a3b8] leading-relaxed">
                    Pesanan ini dihasilkan secara otomatis oleh sistem kami.<br>Mohon untuk tidak membalas email ini secara langsung.
                </p>
            </div>

        </div>
    </div>
</body>
</html>
