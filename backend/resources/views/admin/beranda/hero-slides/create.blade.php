<x-admin-layout>
    <x-admin-header
        title="Tambah Hero Slide"
        :breadcrumbs="[['title' => 'Beranda', 'url' => route('admin.beranda.index')], ['title' => 'Hero Slides', 'url' => route('admin.beranda.hero-slides.index')], ['title' => 'Tambah Baru']]"
    />

    <form action="{{ route('admin.beranda.hero-slides.store') }}" method="POST" enctype="multipart/form-data" id="hero-slide-form">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Text Content -->
                <div class="bg-white  rounded-2xl shadow-premium p-8 border border-gray-100  relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-mitologi-gold/5 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110 duration-700"></div>
                    
                    <h3 class="text-lg font-bold text-mitologi-navy  mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-mitologi-gold rounded-full"></span>
                        Konten Slide
                    </h3>

                    <div class="space-y-6 relative">
                        <div>
                            <x-input-label for="title" :value="__('Judul (Title)')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus placeholder="Masukkan judul utama slide..." />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="subtitle" :value="__('Sub-Judul (Subtitle)')" />
                            <x-text-input id="subtitle" class="block mt-1 w-full" type="text" name="subtitle" :value="old('subtitle')" placeholder="Tambahkan deskripsi singkat..." />
                            <x-input-error :messages="$errors->get('subtitle')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Smart Image Upload with Cropper -->
                <div class="bg-white  rounded-2xl shadow-premium p-8 border border-gray-100 ">
                    <h3 class="text-lg font-bold text-mitologi-navy  mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-mitologi-gold rounded-full"></span>
                        Gambar Background
                        <span class="text-xs bg-mitologi-gold/20 text-mitologi-navy px-2 py-1 rounded-full">Auto 16:9</span>
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- File Input -->
                        <div>
                            <x-input-label for="image" :value="__('Upload Gambar (Format: JPG, PNG, WEBP - Max 5MB)')" class="mb-2" />
                            <input 
                                type="file" 
                                id="image" 
                                name="image_original" 
                                accept="image/jpeg,image/png,image/webp"
                                class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-mitologi-navy file:text-white hover:file:bg-mitologi-navy-light file:cursor-pointer"
                                required
                            >
                            <input type="file" id="cropped-image-input" name="image" style="display: none;">
                        </div>

                        <!-- Preview Container -->
                        <div id="image-preview-container" class="hidden mt-4">
                            <p class="text-sm font-bold text-mitologi-navy mb-2">Preview & Crop:</p>
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
                <div class="bg-white  rounded-2xl shadow-premium p-8 border border-gray-100 ">
                    <h3 class="text-lg font-bold text-mitologi-navy  mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-mitologi-navy rounded-full"></span>
                        Call to Action (Tombol)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="cta_text" :value="__('Teks Tombol')" />
                            <x-text-input id="cta_text" class="block mt-1 w-full" type="text" name="cta_text" :value="old('cta_text')" placeholder="Contoh: Belanja Sekarang" />
                        </div>

                        <div>
                            <x-input-label for="cta_link" :value="__('Link Tujuan')" />
                            <x-text-input id="cta_link" class="block mt-1 w-full" type="text" name="cta_link" :value="old('cta_link')" placeholder="Contoh: /products/category-name" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-8">
                <!-- Media & Status -->
                <div class="bg-white  rounded-2xl shadow-premium p-6 border border-gray-100 ">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">Pengaturan</h3>
                    
                    <div class="space-y-6">
                        <!-- Sort Order -->
                        <div>
                            <x-input-label for="sort_order" :value="__('Urutan Tampil')" />
                            <div class="flex items-center mt-1">
                                <button type="button" onclick="document.getElementById('sort_order').stepDown()" class="p-2 bg-gray-100  rounded-l-lg hover:bg-gray-200  border border-r-0 border-gray-300 ">-</button>
                                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}" class="block w-full text-center border-gray-300   border-x-0 focus:ring-0 focus:border-gray-300" />
                                <button type="button" onclick="document.getElementById('sort_order').stepUp()" class="p-2 bg-gray-100  rounded-r-lg hover:bg-gray-200  border border-l-0 border-gray-300 ">+</button>
                            </div>
                            <x-input-error :messages="$errors->get('sort_order')" class="mt-2" />
                        </div>

                        <!-- Status Toggle -->
                        <div class="flex items-center justify-between p-4 bg-gray-50  rounded-xl">
                            <span class="text-sm font-medium text-gray-700 ">Status Aktif</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-mitologi-navy  rounded-full peer  peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all  peer-checked:bg-mitologi-navy"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white  rounded-2xl shadow-premium p-6 border border-gray-100 ">
                    <button type="submit" id="submit-btn" class="w-full px-4 py-3 bg-mitologi-navy text-white rounded-xl hover:bg-mitologi-navy-light shadow-lg hover:shadow-mitologi-navy/30 transition-all duration-300 font-bold text-lg flex justify-center items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan Hero Slide
                    </button>
                    <p class="text-xs text-center text-slate-500 mt-3">
                        Gambar akan diproses dan di-crop otomatis ke 16:9
                    </p>
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
        
        // Form submission with proper file handling
        document.getElementById('hero-slide-form').addEventListener('submit', function(e) {
            const hasNewImage = document.getElementById('image').files.length > 0;
            
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
        });
    </script>
</x-admin-layout>

