@props(['active' => false, 'href' => '#'])

@php
    $baseClasses = 'flex items-center px-4 py-3.5 text-sm font-medium rounded-xl transition-colors duration-300 group';
    $activeClasses = 'bg-white/10 text-white shadow-glass border-l-4 border-mitologi-gold backdrop-blur-md';
    $inactiveClasses = 'text-gray-400 hover:bg-white/5 hover:text-white';
    
    $classes = $baseClasses . ' ' . ($active ? $activeClasses : $inactiveClasses);
    
    $iconClasses = 'w-5 h-5 mr-3 flex-shrink-0 transition-colors duration-300 ' . ($active ? 'text-mitologi-gold' : 'text-gray-500 group-hover:text-mitologi-gold');
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if(isset($icon))
        <div class="{{ $iconClasses }}">
            {{ $icon }}
        </div>
    @endif
    
    <span>{{ $slot }}</span>
</a>
