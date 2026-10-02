<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-slate-100 hover:bg-slate-200 active:bg-slate-300 text-slate-800 font-bold text-xs uppercase tracking-wider rounded-2xl border border-slate-200 shadow-xs active:scale-[0.99] transition duration-150']) }}>
    {{ $slot }}
</button>
