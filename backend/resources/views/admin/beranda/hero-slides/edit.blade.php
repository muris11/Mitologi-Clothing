<x-admin-layout>
    <x-admin-header
        title="Edit Hero Slide"
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Hero Slides', 'url' => route('admin.beranda.hero-slides.index')], ['title' => 'Edit: ' . $heroSlide->title]]"
    />

    <form action="{{ route('admin.beranda.hero-slides.update', $heroSlide) }}" method="POST" enctype="multipart/form-data" id="hero-slide-form">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Text Content -->
               <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-premium p-8 border border-gray-100 dark:border-gray-700 relative overflow-hidden group">
                      <div class="absolute top-0 right-0 w-32 h-32 bg-mitologi-gold/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110 duration-700"></div>
                      <h3 class="text-lg font-bold text-mitologi-navy dark:text-white mb-6 flex items-center gap-2">
                         <span class="w-1 h-6 bg-mitologi-gold rounded-full"></span>
                         Konten Slide
                     </h3>

                     <div class="space-y-6 relative">
                         <div>
                             <x-input-label for="title" :value="__('Judul (Title)')" />
                             <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $heroSlide->title)" required autofocus />
                             <x-input-error :messages="$errors->get('title')" class="mt-2" />
                         </div>

                         <div>
                             <x-input-label for="subtitle" :value="__('Sub-Judul (Subtitle)')" />
                             <x-text-input id="subtitle" class="block mt-1 w-full" type="text" name="subtitle" :value="old('subtitle', $heroSlide->subtitle)" />
                             <x-input-error :messages="$errors->get('subtitle')" class="mt-2" />
                         </div>
                     </div>
                 </div>

                <!-- Smart Image Upload with Cropper -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-premium p-8 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-mitologi-navy dark:text-white mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-mitologi-gold rounded-full"></span>
                        Gambar Background
                        <span class="text-xs bg-mitologi-gold/20 text-mitologi-navy px-2 py-1 rounded-full">Auto 16:9</span>
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Current Image Preview -->
                        @if($heroSlide->image_url)
                            <div class="mb-4" id="current-image-container">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-sm font-bold text-mitologi-navy">Gambar Saat Ini:</p>
                                    <label class="flex items-center gap-2 text-sm text-red-600 hover:text-red-700 cursor-pointer">
                                        <input type="checkbox" name="delete_image" value="1" id="delete-image-checkbox" class="w-4 h-4 text-red-600 rounded border-gray-300 focus:ring-red-500">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Hapus Gambar
                                        </span>
                                    </label>
                                </div>
                                <div class="relative w-full h-48 rounded-lg overflow-hidden border border-gray-200" id="current-image-preview">
                                    <img src="{{ asset('storage/' . $heroSlide->image_url) }}" alt="Current Image" class="w-full h-full object-cover">
                                    <div class="absolute top-2 right-2 bg-black/50 text-white text-xs px-2 py-1 rounded">
                                        16:9
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- File Input -->
                        <div>
                            <x-input-label for="image" :value="__('Ganti Gambar (Opsional - Format: JPG, PNG, WEBP - Max 5MB)')" class="mb-2" />
                            <input 
                                type="file" 
                                id="image"
                                name="image_original" 
                                accept="image/jpeg,image/png,image/webp"
                                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-mitologi-navy file:text-white hover:file:bg-mitologi-navy-light file:cursor-pointer"
                            >
                            <input type="file" id="cropped-image-input" name="image" style="display: none;">
                            <p class="text-xs text-slate-500 mt-1">Kosongkan jika tidak ingin mengganti gambar</p>
                        </div>

                        <!-- Preview Container (Hidden by default) -->
                        <div id="image-preview-container" class="hidden mt-4">
                            <p class="text-sm font-bold text-mitologi-navy mb-2">Preview Gambar Baru:</p>
                            <div id="cropper-preview" class="border-2 border-dashed border-mitologi-gold/50 rounded-lg p-2 bg-mitologi-gold/5">
                                <!-- Canvas will be inserted here -->
                            </div>
                            <p class="text-xs text-slate-500 mt-2 italic">
                                Tips: Drag area kuning untuk mengatur posisi crop. Gambar akan otomatis di-resize ke 1920x1080px
                            </p>
                            <div class="flex gap-2 mt-3">
                                <button type="button" id="reset-crop" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-bold hover:bg-slate-200 transition-colors">
                                    Reset Crop
                                </button>
                                <button type="button" id="change-image" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg text-sm font-bold hover:bg-slate-200 transition-colors">
                                    Ganti Gambar
                                </button>
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-sm text-blue-800">
                            <strong>Format Ideal:</strong> Upload gambar ukuran berapa saja, sistem akan otomatis crop ke ratio 16:9 (1920x1080px) untuk tampilan optimal di banner.
                        </div>
                    </div>
                </div>

                <!-- Call to Action -->
                 <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-premium p-8 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-mitologi-navy dark:text-white mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-mitologi-navy rounded-full"></span>
                        Call to Action (Tombol)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="cta_text" :value="__('Teks Tombol')" />
                            <x-text-input id="cta_text" class="block mt-1 w-full" type="text" name="cta_text" :value="old('cta_text', $heroSlide->cta_text)" placeholder="Contoh: Belanja Sekarang" />
                        </div>

                        <div>
                            <x-input-label for="cta_link" :value="__('Link Tujuan')" />
                            <x-text-input id="cta_link" class="block mt-1 w-full" type="text" name="cta_link" :value="old('cta_link', $heroSlide->cta_link)" placeholder="Contoh: /products/category-name" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-8">
                 <!-- Media & Status -->
                 <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-premium p-6 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Pengaturan</h3>
                    
                    <div class="space-y-6">
                         <!-- Sort Order -->
                         <div>
                             <x-input-label for="sort_order" :value="__('Urutan Tampil')" />
                             <div class="flex items-center mt-1">
                                 <button type="button" onclick="document.getElementById('sort_order').stepDown()" class="p-2 bg-gray-100 dark:bg-gray-700 rounded-l-lg hover:bg-gray-200 dark:hover:bg-gray-600 border border-r-0 border-gray-300 dark:border-gray-600">-</button>
                                 <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $heroSlide->sort_order) }}" class="block w-full text-center border-gray-300 dark:border-gray-600 dark:bg-gray-900 border-x-0 focus:ring-0 focus:border-gray-300" />
                                 <button type="button" onclick="document.getElementById('sort_order').stepUp()" class="p-2 bg-gray-100 dark:bg-gray-700 rounded-r-lg hover:bg-gray-200 dark:hover:bg-gray-600 border border-l-0 border-gray-300 dark:border-gray-600">+</button>
                             </div>
                             <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                         </div>

                         <!-- Status Toggle -->
                         <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl">
                             <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Status Aktif</span>
                             <label class="relative inline-flex items-center cursor-pointer">
                                 <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $heroSlide->is_active) ? 'checked' : '' }}>
                                 <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-mitologi-navy dark:peer-focus:ring-mitologi-gold rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-mitologi-navy"></div>
                             </label>
                         </div>
                     </div>
                 </div>

                 <!-- Actions -->
                 <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-premium p-6 border border-gray-100 dark:border-gray-700">
                     <button type="submit" id="submit-btn" class="w-full px-4 py-3 bg-mitologi-navy text-white rounded-xl hover:bg-mitologi-navy-light shadow-lg hover:shadow-mitologi-navy/30 transition-all duration-300 font-bold text-lg flex justify-center items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                         Perbarui Slide
                     </button>
                     
                     <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 text-center">
                         <span class="text-xs text-gray-500">Terakhir diubah: {{ $heroSlide->updated_at->diffForHumans() }}</span>
                     </div>
                 </div>
            </div>
        </div>
    </form>

    <!-- Initialize Cropper -->
    <script src="{{ asset('js/hero-image-cropper.js') }}"></script>
    <script>
        let cropper = null;
        
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            // Show preview container
            document.getElementById('image-preview-container').classList.remove('hidden');
            
            // Initialize cropper
            cropper = new HeroImageCropper();
            cropper.init(file, 'cropper-preview', function() {
                console.log('Cropper ready');
            });
        });
        
        // Reset crop
        document.getElementById('reset-crop').addEventListener('click', function() {
            if (cropper) cropper.reset();
        });
        
        // Change image
        document.getElementById('change-image').addEventListener('click', function() {
            document.getElementById('image').value = '';
            document.getElementById('image-preview-container').classList.add('hidden');
            document.getElementById('cropper-preview').innerHTML = '';
            document.getElementById('cropped-image-input').value = '';
            cropper = null;
        });
        
        // Delete image checkbox handler
        const deleteCheckbox = document.getElementById('delete-image-checkbox');
        const currentImagePreview = document.getElementById('current-image-preview');
        
        if (deleteCheckbox && currentImagePreview) {
            deleteCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    currentImagePreview.style.opacity = '0.3';
                    currentImagePreview.style.filter = 'grayscale(100%)';
                    if (cropper) {
                        document.getElementById('image-preview-container').classList.add('hidden');
                        document.getElementById('image').value = '';
                    }
                } else {
                    currentImagePreview.style.opacity = '1';
                    currentImagePreview.style.filter = 'none';
                }
            });
        }
        
        // Form submission with proper file handling
        document.getElementById('hero-slide-form').addEventListener('submit', function(e) {
            // Check if delete image is checked
            const deleteImage = deleteCheckbox && deleteCheckbox.checked;
            const hasNewImage = document.getElementById('image').files.length > 0;
            
            // If deleting image, no need to process new image
            if (deleteImage) {
                return; // Submit normally
            }
            
            // Only process if there's a new image
            if (hasNewImage && cropper) {
                e.preventDefault();
                
                const submitBtn = document.getElementById('submit-btn');
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses gambar...';
                
                console.log('Starting crop process...');
                
                cropper.getCroppedImage(function(croppedFile) {
                    console.log('Cropped file received:', croppedFile ? croppedFile.name : 'null');
                    
                    if (croppedFile && croppedFile.size > 0) {
                        // Create DataTransfer to set the file
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(croppedFile);
                        const fileInput = document.getElementById('cropped-image-input');
                        fileInput.files = dataTransfer.files;
                        
                        console.log('File attached to input:', fileInput.files.length > 0, 'Size:', fileInput.files[0]?.size);
                        console.log('File name:', fileInput.files[0]?.name, 'Type:', fileInput.files[0]?.type);
                        
                        // Verify file is properly attached
                        if (fileInput.files.length === 0 || fileInput.files[0].size === 0) {
                            console.error('File attachment failed!');
                            alert('Error: Gagal memproses gambar. Silakan coba lagi.');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalBtnText;
                            return;
                        }
                    } else {
                        console.error('No cropped file received or file is empty');
                        alert('Error: Gagal memproses gambar. Silakan coba lagi.');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                        return;
                    }
                    
                    // Now submit the form
                    submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...';
                    
                    console.log('Submitting form...');
                    
                    // Small delay to ensure file is attached
                    setTimeout(() => {
                        e.target.submit();
                    }, 200);
                });
            }
            // If no new image and not deleting, form will submit normally
        });
    </script>
</x-admin-layout>
