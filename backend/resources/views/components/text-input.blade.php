@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full px-4 py-3 border border-gray-200  bg-gray-50/50  text-gray-900  focus:border-mitologi-navy focus:ring-2 focus:ring-mitologi-navy/20 rounded-xl shadow-sm placeholder-gray-400 transition-all duration-200 disabled:opacity-50 disabled:bg-gray-100 disabled:cursor-not-allowed']) !!}>

