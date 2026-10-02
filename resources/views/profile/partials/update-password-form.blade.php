<section>
    <header class="border-b border-slate-100 pb-2 mb-3.5">
        <h2 class="text-sm font-bold text-slate-900">
            Perbarui Kata Sandi
        </h2>
        <p class="text-xs text-slate-500 mt-0.5">
            Gunakan kombinasi sandi yang kuat untuk menjaga keamanan akun Anda
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi Saat Ini *</label>
            <input id="update_password_current_password" name="current_password" type="password" maxlength="15" placeholder="••••••••" autocomplete="current-password" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-2xs transition" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1 text-xs font-medium text-rose-600" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
            <div>
                <label for="update_password_password" class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi Baru *</label>
                <input id="update_password_password" name="password" type="password" maxlength="15" placeholder="••••••••" autocomplete="new-password" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-2xs transition" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1 text-xs font-medium text-rose-600" />
            </div>

            <div>
                <label for="update_password_password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1">Konfirmasi Sandi Baru *</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" maxlength="15" placeholder="••••••••" autocomplete="new-password" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-2xs transition" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1 text-xs font-medium text-rose-600" />
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="px-4 py-2 rounded-lg font-semibold text-xs text-white bg-slate-900 hover:bg-slate-800 shadow-2xs transition">
                Perbarui Sandi
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs font-medium text-emerald-700">
                    Kata sandi berhasil diperbarui.
                </p>
            @endif
        </div>
    </form>
</section>
