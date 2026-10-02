<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Masuk ke Akun</h2>
        <p class="text-xs text-slate-500 mt-1">Gunakan kredensial akun Anda untuk mengakses sistem</p>
    </div>

    <!-- Session Status -->
    @if(session('status'))
        <div class="mb-4 text-xs font-medium text-emerald-800 bg-emerald-50 p-3 rounded-xl border border-emerald-200">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" id="loginForm" class="space-y-4">
        @csrf

        <!-- Anti-Bot Honeypot -->
        <div class="hidden" aria-hidden="true">
            <input type="text" name="hp_field" tabindex="-1" autocomplete="off" value="">
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                Alamat Email
            </label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                maxlength="50"
                value="{{ old('email') }}" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-2xs transition" 
                placeholder="nama@sekolah.sch.id"
                required 
                autofocus 
                autocomplete="username" />
            @error('email')
                <p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-semibold text-slate-700">
                    Kata Sandi
                </label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-slate-500 hover:text-slate-800" href="{{ route('password.request') }}">
                        Lupa sandi?
                    </a>
                @endif
            </div>

            <input 
                id="password" 
                type="password" 
                name="password" 
                maxlength="15"
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-2xs transition" 
                placeholder="••••••••"
                required 
                autocomplete="current-password" />
            @error('password')
                <p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-slate-900 focus:ring-slate-900 h-3.5 w-3.5" name="remember">
                <span class="text-xs text-slate-600">Ingat Saya</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button id="btnSubmit" type="submit" class="w-full py-2.5 rounded-xl font-semibold text-xs text-white bg-slate-900 hover:bg-slate-800 shadow-sm transition">
            Masuk
        </button>

        <div class="text-center pt-3 border-t border-slate-100">
            <p class="text-xs text-slate-500">
                Belum memiliki akun? 
                <a href="{{ route('register') }}" class="font-semibold text-slate-900 hover:underline">Daftar Akun Siswa</a>
            </p>
        </div>
    </form>
</x-guest-layout>