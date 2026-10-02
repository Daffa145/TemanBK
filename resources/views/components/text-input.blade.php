@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-4 py-3 rounded-2xl border border-slate-300 bg-white text-slate-900 text-sm focus:ring-4 focus:ring-brand-500/20 focus:border-brand-500 placeholder-slate-400 shadow-xs transition duration-150']) }}>
