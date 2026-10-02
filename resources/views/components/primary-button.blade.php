<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-brand-600 hover:bg-brand-700 active:bg-brand-800 focus:outline-none focus:ring-4 focus:ring-brand-500/20 rounded-2xl font-black text-xs text-white uppercase tracking-wider shadow-md shadow-brand-500/20 active:scale-[0.99] transition duration-150']) }}>
    {{ $slot }}
</button>
