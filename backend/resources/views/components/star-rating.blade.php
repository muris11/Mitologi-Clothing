@props(['name', 'value' => 5])

<div x-data="{ 
    rating: {{ $value }},
    hoverRating: 0
}" class="flex items-center gap-3">
    <input type="hidden" name="{{ $name }}" :value="rating">
    
    <div class="flex items-center gap-1" @mouseleave="hoverRating = 0">
        <template x-for="i in 5">
            <button type="button" 
                @click="rating = i" 
                @mouseover="hoverRating = i"
                class="focus:outline-none transition-all duration-200 transform hover:scale-110 p-1"
                :class="{
                    'text-mitologi-gold drop-shadow-md': i <= (hoverRating || rating), 
                    'text-gray-200 ': i > (hoverRating || rating)
                }"
            >
                <svg class="w-8 h-8 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                </svg>
            </button>
        </template>
    </div>
    
    <div class="bg-gray-100  px-3 py-1 rounded-lg">
        <span class="text-sm font-bold text-mitologi-navy " x-text="rating + ' / 5'"></span>
    </div>
</div>

