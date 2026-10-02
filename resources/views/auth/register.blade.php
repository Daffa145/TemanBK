<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Pendaftaran Akun Siswa</h2>
        <p class="text-xs text-slate-500 mt-1">Lengkapi data untuk memulai layanan bimbingan konseling</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-3.5">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">
                Nama Lengkap Siswa *
            </label>
            <input id="name" type="text" name="name" maxlength="25" value="{{ old('name') }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-2xs transition" placeholder="Muhammad Rizky" required autofocus />
            @error('name')
                <p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- NIS & Kelas -->
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="nis" class="block text-xs font-semibold text-slate-700 mb-1">
                    NIS
                </label>
                <input id="nis" type="text" name="nis" maxlength="7" value="{{ old('nis') }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-2xs transition" placeholder="2026100" />
                @error('nis')
                    <p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="kelas" class="block text-xs font-semibold text-slate-700 mb-1">
                    Kelas
                </label>
                <input id="kelas" type="text" name="kelas" maxlength="10" value="{{ old('kelas') }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-2xs transition" placeholder="XII RPL 1" />
                @error('kelas')
                    <p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Jurusan & No HP -->
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="jurusan" class="block text-xs font-semibold text-slate-700 mb-1">
                    Jurusan
                </label>
                <input id="jurusan" type="text" name="jurusan" maxlength="7" value="{{ old('jurusan') }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-2xs transition" placeholder="RPL/TKJ" />
                @error('jurusan')
                    <p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="no_hp" class="block text-xs font-semibold text-slate-700 mb-1">
                    No. WhatsApp / HP
                </label>
                <input id="no_hp" type="text" name="no_hp" maxlength="14" value="{{ old('no_hp') }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-2xs transition" placeholder="081234567890" />
                @error('no_hp')
                    <p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">
                Alamat Email *
            </label>
            <input id="email" type="email" name="email" maxlength="50" value="{{ old('email') }}" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-2xs transition" placeholder="siswa@sekolah.sch.id" required autocomplete="username" />
            @error('email')
                <p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">
                    Kata Sandi *
                </label>
                <input id="password" type="password" name="password" maxlength="15" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-2xs transition" placeholder="••••••••" required autocomplete="new-password" />
                @error('password')
                    <p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1">
                    Konfirmasi Sandi *
                </label>
                <input id="password_confirmation" type="password" name="password_confirmation" maxlength="15" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-2xs transition" placeholder="••••••••" required autocomplete="new-password" />
                @error('password_confirmation')
                    <p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-2.5 rounded-xl font-semibold text-xs text-white bg-slate-900 hover:bg-slate-800 shadow-sm transition mt-1">
            Daftar Akun
        </button>

        <div class="text-center pt-2">
            <p class="text-xs text-slate-500">
                Sudah memiliki akun? 
                <a href="{{ route('login') }}" class="font-semibold text-slate-900 hover:underline">Masuk di sini</a>
            </p>
        </div>
    </form>
</x-guest-layout>
