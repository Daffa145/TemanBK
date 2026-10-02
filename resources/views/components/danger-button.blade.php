<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white font-black text-xs uppercase tracking-wider rounded-2xl shadow-md shadow-rose-500/20 active:scale-[0.99] transition duration-150']) }}>
    {{ $slot }}
</button>
