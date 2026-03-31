<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl font-bold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-wider shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-mitologi-gold focus:ring-offset-2 disabled:opacity-25 transition-all duration-300 transform hover:-translate-y-0.5']) }}>
    {{ $slot }}
</button>
