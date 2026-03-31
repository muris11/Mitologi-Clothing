<x-admin-layout>
    <x-admin-header 
        title="Detail Produk & Ulasan" 
        :breadcrumbs="[['title' => 'Produk', 'url' => route('admin.products.index')], ['title' => 'Detail & Ulasan']]"
        :action_text="'Edit Produk'"
        :action_url="route('admin.products.edit', $product)"
    />

    <div class="mb-8 flex gap-4 border-b border-gray-200/80 pb-5">
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-200 rounded-xl font-semibold text-xs text-gray-700 uppercase tracking-[0.16em] shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-mitologi-navy focus:ring-offset-2 transition-colors duration-150">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Product Info Card -->
        <div class="admin-panel col-span-1 overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-2">Informasi Produk</h3>
                <div class="flex flex-col items-center mb-4">
                    @if($product->featured_image_url)
                        <img src="{{ $product->featured_image_url }}" alt="{{ $product->title }}" class="w-40 h-40 object-cover rounded-xl shadow-sm border border-gray-100 mb-4">
                    @else
                        <div class="w-40 h-40 bg-gray-50 rounded-xl flex items-center justify-center mb-4 border border-gray-100">
                            <i class="fas fa-image text-4xl text-gray-300"></i>
                        </div>
                    @endif
                    <h4 class="text-xl font-bold text-center text-gray-900 leading-tight">{{ $product->title }}</h4>
                    <p class="text-sm text-gray-500 mt-2 bg-gray-50 px-3 py-1 rounded-full"><i class="fas fa-box-open mr-1"></i> Stok Total: <span class="font-bold text-gray-700">{{ $product->variants->sum('stock') }}</span></p>
                    <div class="flex items-center gap-2 mt-4 bg-yellow-50 px-4 py-2 rounded-lg border border-yellow-100 w-full justify-center">
                        <i class="fas fa-star text-yellow-500 text-lg"></i>
                        <span class="font-bold text-yellow-700 text-lg">{{ $product->reviews->count() > 0 ? round($product->reviews->avg('rating'), 1) : '0.0' }}</span>
                        <span class="text-sm text-yellow-600">({{ $product->reviews->count() }} Ulasan)</span>
                    </div>
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-100 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 text-sm"><i class="fas fa-tag w-5"></i> Harga Terendah</span>
                        <span class="font-bold text-mitologi-navy">Rp {{ number_format($product->price_range['minVariantPrice']['amount'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 text-sm"><i class="fas fa-tags w-5"></i> Harga Tertinggi</span>
                        <span class="font-bold text-mitologi-navy">Rp {{ number_format($product->price_range['maxVariantPrice']['amount'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 text-sm"><i class="fas fa-info-circle w-5"></i> Status</span>
                        @if($product->available_for_sale)
                            <span class="px-2.5 py-1 text-[10px] uppercase tracking-wider font-bold rounded-full bg-green-100 text-green-800">Aktif</span>
                        @else
                            <span class="px-2.5 py-1 text-[10px] uppercase tracking-wider font-bold rounded-full bg-red-100 text-red-800">Draft / Habis</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Table Card -->
        <div class="admin-panel col-span-1 md:col-span-2 overflow-hidden flex flex-col h-full">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/30">
                <h3 class="text-lg font-bold text-gray-800 flex items-center"><i class="fas fa-comments text-mitologi-gold mr-3"></i> Ulasan Pelanggan</h3>
                <span class="bg-mitologi-navy text-white text-xs font-bold px-2.5 py-1 rounded-full">{{ $product->reviews->count() }} Total</span>
            </div>
            <div class="overflow-x-auto flex-1 h-full">
                <table class="w-full text-left border-collapse min-h-[300px]">
                    <thead>
                        <tr class="bg-gray-50 uppercase text-[10px] tracking-wider text-gray-500">
                            <th class="py-4 px-6 font-bold border-b border-gray-100">Pelanggan & Rating</th>
                            <th class="py-4 px-6 font-bold border-b border-gray-100 w-1/2">Ulasan</th>
                            <th class="py-4 px-6 font-bold border-b border-gray-100 w-16 text-center">Status</th>
                            <th class="py-4 px-6 font-bold border-b border-gray-100 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($product->reviews as $review)
                        <tr class="hover:bg-gray-50/50 transition-colors {{ !$review->is_visible ? 'opacity-50 grayscale-[50%]' : '' }}">
                            <td class="py-5 px-6 align-top">
                                <div class="flex items-center gap-3 mb-3">
                                    @if($review->user->avatar_url)
                                        <img src="{{ $review->user->avatar_url }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover shadow-sm border border-gray-100">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-mitologi-navy to-mitologi-navy-light text-white flex items-center justify-center font-bold text-xs shadow-sm">
                                            {{ substr($review->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-gray-900 text-sm">{{ $review->user->name }}</div>
                                        <div class="text-[11px] text-gray-500">{{ $review->created_at->format('d M Y, H:i') }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 text-yellow-400 text-sm bg-yellow-50/50 inline-flex px-2 py-1 rounded-md border border-yellow-100/50">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            </td>
                            <td class="py-5 px-6 align-top">
                                <div class="relative">
                                    <i class="fas fa-quote-left absolute -top-1 -left-2 text-gray-200 text-2xl z-0"></i>
                                    <p class="text-sm text-gray-700 italic relative z-10 leading-relaxed mb-4 pl-3">"{!! nl2br(e($review->comment)) !!}"</p>
                                </div>
                                
                                @if($review->admin_reply)
                                    <div class="bg-mitologi-navy/5 p-4 rounded-xl border border-mitologi-navy/10 relative mt-4 shadow-sm">
                                        <div class="flex items-center gap-2 mb-2">
                                            <div class="w-5 h-5 rounded bg-mitologi-gold text-white flex items-center justify-center text-[10px] font-bold">M</div>
                                            <span class="text-xs font-bold text-mitologi-navy">Tanggapan Toko</span>
                                        </div>
                                        <p class="text-sm text-gray-800 leading-relaxed pl-7">{!! nl2br(e($review->admin_reply)) !!}</p>
                                        <div class="text-[10px] text-gray-400 mt-2 text-right">
                                            {{ \Carbon\Carbon::parse($review->admin_replied_at)->format('d M Y, H:i') }}
                                        </div>
                                    </div>
                                @else
                                    <button onclick="openReplyModal({{ $review->id }})" class="inline-flex items-center text-xs font-semibold text-mitologi-gold hover:text-yellow-600 transition-colors bg-yellow-50 px-3 py-1.5 rounded-md hover:bg-yellow-100 border border-yellow-100 mt-2">
                                        <i class="fas fa-reply mr-1.5"></i> Balas Ulasan
                                    </button>
                                @endif
                            </td>
                            <td class="py-5 px-6 text-center align-middle">
                                <form action="{{ route('admin.products.reviews.toggle', [$product, $review]) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="group relative inline-flex items-center justify-center p-2 focus:outline-none" title="{{ $review->is_visible ? 'Sembunyikan dari Publik' : 'Tampilkan ke Publik' }}">
                                        @if($review->is_visible)
                                            <div class="absolute inset-0 bg-green-100 rounded-lg scale-0 group-hover:scale-100 transition-transform"></div>
                                            <i class="fas fa-eye text-green-500 relative z-10 text-lg"></i>
                                        @else
                                            <div class="absolute inset-0 bg-gray-100 rounded-lg scale-0 group-hover:scale-100 transition-transform"></div>
                                            <i class="fas fa-eye-slash text-gray-400 relative z-10 text-lg"></i>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td class="py-5 px-6 text-right align-middle">
                                <div class="flex flex-col items-end gap-2">
                                    @if($review->admin_reply)
                                        <button onclick='openReplyModal({{ $review->id }}, @js($review->admin_reply))' class="inline-flex items-center justify-center rounded-lg border border-blue-200 bg-blue-50 p-2 text-blue-600 shadow-sm transition-colors hover:bg-blue-100" title="Edit Balasan">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endif
                                    <form action="{{ route('admin.products.reviews.destroy', [$product, $review]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini? Tindakan ini tidak dapat dibatalkan.');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-red-200 bg-red-50 p-2 text-red-600 shadow-sm transition-colors hover:bg-red-100" title="Hapus Ulasan">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Reply Modal for this Review -->
                        <div id="replyModal-{{ $review->id }}" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4 opacity-0 transition-opacity duration-300" style="z-index: 9999;">
                            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-300 border border-gray-100">
                                <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/80">
                                    <h3 class="text-base font-bold text-gray-900 flex items-center"><i class="fas fa-reply text-mitologi-gold mr-2"></i> Balas Ulasan</h3>
                                    <button type="button" onclick="closeReplyModal({{ $review->id }})" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <form action="{{ route('admin.products.reviews.reply', [$product, $review]) }}" method="POST">
                                    @csrf
                                    <div class="p-6">
                                        <div class="mb-5 p-4 bg-gray-50 rounded-xl border border-gray-100 relative">
                                            <div class="absolute top-4 right-4 text-xs text-gray-400">{{ $review->created_at->format('d M Y') }}</div>
                                            <div class="font-bold text-gray-900 text-sm mb-2"><i class="fas fa-user-circle text-gray-400 mr-1"></i> {{ $review->user->name }}</div>
                                            <div class="flex items-center gap-1 text-yellow-400 text-xs mb-3">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                                @endfor
                                            </div>
                                            <p class="text-sm text-gray-700 italic border-l-2 border-gray-300 pl-3">"{{ $review->comment }}"</p>
                                        </div>
                                        
                                        <div>
                                            <label for="admin_reply_{{ $review->id }}" class="block text-sm font-bold text-gray-700 mb-2">Tanggapan Toko <span class="text-red-500">*</span></label>
                                            <textarea 
                                                name="admin_reply" 
                                                id="admin_reply_{{ $review->id }}" 
                                                rows="4" 
                                                class="w-full border-gray-300 focus:border-mitologi-gold focus:ring focus:ring-mitologi-gold/20 rounded-xl shadow-sm text-sm p-3 transition-shadow" 
                                                placeholder="Tuliskan apresiasi, permintaan maaf, atau solusi kepada pelanggan. Balasan ini akan terlihat oleh publik..." 
                                                required
                                            >{{ old('admin_reply', $review->admin_reply) }}</textarea>
                                            @error('admin_reply')
                                                <p class="text-red-500 text-xs mt-1 bg-red-50 inline-block px-2 py-1 rounded">{{ $message }}</p>
                                            @enderror
                                            <p class="text-[10px] text-gray-500 mt-2"><i class="fas fa-info-circle mr-1"></i> Tanggapan akan ditampilkan di bawah ulasan pengguna dengan penanda "Tanggapan Toko".</p>
                                        </div>
                                    </div>
                                    <div class="p-4 border-t border-gray-100 bg-gray-50/80 flex justify-end gap-3">
                                        <button type="button" onclick="closeReplyModal({{ $review->id }})" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">Batal</button>
                                        <button type="submit" class="px-5 py-2 bg-mitologi-navy text-white rounded-lg text-sm font-bold hover:bg-mitologi-navy-light transition-colors shadow-sm focus:ring-4 focus:ring-mitologi-navy/20 flex items-center">
                                            <i class="fas fa-paper-plane mr-2 text-xs"></i> Kirim Balasan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="4" class="py-20 px-6 text-center text-gray-500 align-middle">
                                <div class="flex flex-col items-center justify-center h-full">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-gray-100 shadow-sm relative">
                                        <i class="far fa-comment-dots text-4xl text-gray-300"></i>
                                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-white rounded-full flex items-center justify-center shadow-sm">
                                            <i class="fas fa-search text-gray-300 text-[10px]"></i>
                                        </div>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-700 mb-1">Belum Ada Ulasan</h4>
                                    <p class="text-sm text-gray-500 max-w-sm">Produk ini belum menerima ulasan dari pelanggan. Ulasan akan muncul di sini setelah pembeli membagikan pengalaman mereka.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripts for modales -->
    <script>
        function openReplyModal(id, currentReply = '') {
            const modal = document.getElementById('replyModal-' + id);
            if (!modal) return;
            
            modal.classList.remove('hidden');
            // Trigger reflow for animation
            void modal.offsetWidth;
            
            modal.classList.remove('opacity-0');
            modal.firstElementChild.classList.remove('scale-95');
            
            if (currentReply) {
                const textarea = document.getElementById('admin_reply_' + id);
                if (textarea) textarea.value = currentReply;
            }
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }

        function closeReplyModal(id) {
            const modal = document.getElementById('replyModal-' + id);
            if (!modal) return;
            
            modal.classList.add('opacity-0');
            modal.firstElementChild.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
            
            // Restore body scroll
            document.body.style.overflow = '';
        }
    </script>
</x-admin-layout>
