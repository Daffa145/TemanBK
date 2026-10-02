<section>
    <header class="border-b border-slate-100 pb-2 mb-3.5">
        <h2 class="text-sm font-bold text-slate-900">
            Hapus Akun Pengguna
        </h2>
        <p class="text-xs text-slate-500 mt-0.5">
            Tindakan ini permanen. Semua data akun dan riwayat bimbingan Anda akan dihapus secara permanen.
        </p>
    </header>

    <div class="p-3.5 rounded-xl bg-rose-50/70 border border-rose-200/80 mb-4">
        <p class="text-xs text-rose-800 leading-relaxed">
            Sebelum menghapus akun, pastikan Anda telah menyimpan informasi atau catatan penting yang mungkin Anda butuhkan di masa mendatang.
        </p>
    </div>

    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-4 py-2 rounded-lg font-semibold text-xs text-white bg-rose-600 hover:bg-rose-700 shadow-2xs transition">
        Hapus Akun
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-4">
            @csrf
            @method('delete')

            <h3 class="text-base font-bold text-slate-900">
                Konfirmasi Penghapusan Akun
            </h3>

            <p class="text-xs text-slate-600 leading-relaxed">
                Apakah Anda yakin ingin menghapus akun ini? Masukkan kata sandi Anda untuk mengonfirmasi tindakan permanen ini.
            </p>

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi Konfirmasi *</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    maxlength="15"
                    class="w-full px-3.5 py-2 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-rose-500 focus:border-rose-500 shadow-2xs"
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1 text-xs font-medium text-rose-600" />
            </div>

            <div class="flex justify-end items-center gap-2.5 pt-3 border-t border-slate-100">
                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="px-3.5 py-2 rounded-lg text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition">
                    Batal
                </button>

                <button
                    type="submit"
                    class="px-4 py-2 rounded-lg text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 transition">
                    Ya, Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>
