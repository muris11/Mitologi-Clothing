@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-sm text-mitologi-navy dark:text-gray-200 mb-2']) }}>
    {{ $value ?? $slot }}
</label>
