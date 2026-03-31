@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full px-4 py-3 border border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800 text-gray-900 dark:text-white focus:border-mitologi-navy focus:ring-2 focus:ring-mitologi-navy/20 rounded-xl shadow-sm placeholder-gray-400 transition-all duration-200 disabled:opacity-50 disabled:bg-gray-100 disabled:cursor-not-allowed']) !!}>{{ $slot }}</textarea>
