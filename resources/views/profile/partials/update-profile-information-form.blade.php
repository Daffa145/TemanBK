<section>
    <header class="border-b border-slate-100 pb-2 mb-3.5">
        <h2 class="text-sm font-bold text-slate-900">
            Informasi Profil Pengguna
        </h2>
        <p class="text-xs text-slate-500 mt-0.5">
            Perbarui data identitas dan kontak akun Anda
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-3">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                <input id="name" name="name" type="text" maxlength="25" value="{{ old('name', $user->name) }}" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-2xs transition" required autofocus autocomplete="name" />
                @error('name')
                    <p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email *</label>
                <input id="email" name="email" type="email" maxlength="50" value="{{ old('email', $user->email) }}" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-2xs transition" required autocomplete="username" />
                @error('email')
                    <p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Role-Specific Fields -->
        @if($user->isSiswa())
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider block">Data Akademik Siswa</span>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                    <div>
                        <label for="nis" class="block text-[11px] font-medium text-slate-600 mb-0.5">NIS</label>
                        <input id="nis" name="nis" type="text" maxlength="7" value="{{ old('nis', $user->nis) }}" placeholder="2026100" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs" />
                        @error('nis')
                            <p class="text-rose-600 text-[11px] font-medium mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="kelas" class="block text-[11px] font-medium text-slate-600 mb-0.5">Kelas</label>
                        <input id="kelas" name="kelas" type="text" maxlength="10" value="{{ old('kelas', $user->kelas) }}" placeholder="XII RPL 1" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs" />
                        @error('kelas')
                            <p class="text-rose-600 text-[11px] font-medium mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="jurusan" class="block text-[11px] font-medium text-slate-600 mb-0.5">Jurusan</label>
                        <input id="jurusan" name="jurusan" type="text" maxlength="7" value="{{ old('jurusan', $user->jurusan) }}" placeholder="RPL/TKJ" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs" />
                        @error('jurusan')
                            <p class="text-rose-600 text-[11px] font-medium mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        @elseif($user->isGuruBk())
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 space-y-2">
                <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider block">Data Guru BK</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <div>
                        <label for="nip" class="block text-[11px] font-medium text-slate-600 mb-0.5">NIP</label>
                        <input id="nip" name="nip" type="text" maxlength="50" value="{{ old('nip', $user->nip) }}" placeholder="198001012005011001" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs" />
                        @error('nip')
                            <p class="text-rose-600 text-[11px] font-medium mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="spesialisasi_kategori" class="block text-[11px] font-medium text-slate-600 mb-0.5">Bidang Spesialisasi</label>
                        <input id="spesialisasi_kategori" name="spesialisasi_kategori" type="text" value="{{ old('spesialisasi_kategori', $user->spesialisasi_kategori) }}" placeholder="Belajar, Karier, Masa Depan" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs" />
                        @error('spesialisasi_kategori')
                            <p class="text-rose-600 text-[11px] font-medium mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        @endif

        <div>
            <label for="no_hp" class="block text-xs font-semibold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
            <input id="no_hp" name="no_hp" type="text" maxlength="14" value="{{ old('no_hp', $user->no_hp) }}" placeholder="081234567890" class="w-full px-3.5 py-2 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-2xs" />
            @error('no_hp')
                <p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>
            @enderror
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div>
                <p class="text-xs mt-2 text-slate-800">
                    Alamat email Anda belum terverifikasi.
                    <button form="send-verification" class="underline text-xs text-slate-600 hover:text-slate-900 font-bold">
                        Kirim ulang tautan verifikasi.
                    </button>
                </p>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 font-bold text-xs text-emerald-600">
                        Tautan verifikasi baru telah dikirim ke email Anda.
                    </p>
                @endif
            </div>
        @endif

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="px-4 py-2 rounded-lg font-semibold text-xs text-white bg-slate-900 hover:bg-slate-800 shadow-2xs transition">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs font-medium text-emerald-700">
                    Data profil berhasil diperbarui.
                </p>
            @endif
        </div>
    </form>
</section>
