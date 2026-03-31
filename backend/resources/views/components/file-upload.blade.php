@props(['name', 'label' => 'Upload File', 'accept' => 'image/*', 'preview' => null])

<div x-data="{ 
    previewUrl: '{{ $preview ? asset('storage/' . $preview) : '' }}',
    handleFileChange(event) {
        const file = event.target.files[0];
        if (file) {
            this.previewUrl = URL.createObjectURL(file);
        }
    },
    removePreview() {
        this.previewUrl = '';
        document.getElementById('{{ $name }}').value = '';
    }
}" class="w-full">
    
    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">
        {{ $label }}
    </label>

    <div class="flex items-start space-x-4">
        <!-- Preview Box -->
        <div x-show="previewUrl" class="relative w-32 h-32 bg-gray-100 rounded-lg border border-gray-200 overflow-hidden flex-shrink-0">
            <img :src="previewUrl" class="w-full h-full object-cover">
            <button type="button" @click="removePreview" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 focus:outline-none">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- File Input -->
        <div class="flex-1">
            <input 
                id="{{ $name }}" 
                type="file" 
                name="{{ $name }}" 
                accept="{{ $accept }}"
                class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-lg file:border-0
                    file:text-sm file:font-semibold
                    file:bg-mitologi-navy/10 file:text-mitologi-navy
                    hover:file:bg-mitologi-navy/20
                    cursor-pointer"
                @change="handleFileChange"
            />
            <p class="mt-2 text-xs text-gray-500">FORMAT: JPG, PNG, WEBP (Max. 2MB)</p>
        </div>
    </div>
    
    @error($name)
        <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
    @enderror
</div>
