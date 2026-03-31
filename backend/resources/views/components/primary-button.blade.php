<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-mitologi-navy border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-wider hover:bg-mitologi-navy-light active:bg-mitologi-navy focus:outline-none focus:ring-2 focus:ring-mitologi-gold focus:ring-offset-2 transition-all duration-300 shadow-lg hover:shadow-mitologi-navy/40 transform hover:-translate-y-0.5']) }}>
    {{ $slot }}
</button>
