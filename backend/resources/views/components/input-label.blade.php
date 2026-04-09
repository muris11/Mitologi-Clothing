@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-sm text-mitologi-navy  mb-2']) }}>
    {{ $value ?? $slot }}
</label>

