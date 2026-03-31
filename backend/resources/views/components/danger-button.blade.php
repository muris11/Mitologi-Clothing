<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-wider hover:bg-red-500 shadow-lg hover:shadow-red-500/30 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-300 transform hover:-translate-y-0.5']) }}>
    {{ $slot }}
</button>
