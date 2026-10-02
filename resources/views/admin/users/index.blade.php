<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6" x-data="userManagement()">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block mb-1">Manajemen Pengguna</span>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Kelola Akun Siswa & Guru BK</h1>
                <p class="text-xs text-slate-500 mt-0.5">Tambah akun baru, perbarui data profil, atau ubah status peran.</p>
            </div>

            <button @click="openCreateModal()" class="px-4 py-2.5 rounded-xl font-semibold text-xs text-white bg-slate-900 hover:bg-slate-800 shadow-sm transition">
                + Tambah Pengguna
            </button>
        </div>

        <!-- Filter & Search Toolbar -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="flex items-center gap-1 overflow-x-auto pb-1 md:pb-0">
                <a href="{{ route('admin.users.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ !$roleFilter ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    Semua
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'siswa']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $roleFilter === 'siswa' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    Siswa
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'guru_bk']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $roleFilter === 'guru_bk' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    Guru BK
                </a>
                <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $roleFilter === 'admin' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                    Admin
                </a>
            </div>

            <form action="{{ route('admin.users.index') }}" method="GET" class="flex items-center gap-2">
                @if($roleFilter)
                    <input type="hidden" name="role" value="{{ $roleFilter }}">
                @endif
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search }}" 
                    placeholder="Cari nama, email, NIS, NIP..." 
                    class="px-3.5 py-1.5 rounded-xl border border-slate-300 text-xs text-slate-900 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 w-60 shadow-2xs" />
                <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                    Cari
                </button>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto max-h-[315px] overflow-y-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold uppercase tracking-wider sticky top-0 z-10">
                        <tr>
                            <th class="p-3.5">Nama & Email</th>
                            <th class="p-3.5">Peran</th>
                            <th class="p-3.5">Identitas Akademik</th>
                            <th class="p-3.5">Kontak</th>
                            <th class="p-3.5">Kesiapan Guru</th>
                            <th class="p-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-3.5">
                                    <div class="font-semibold text-slate-900">{{ $user->name }}</div>
                                    <div class="text-[11px] text-slate-500 font-mono">{{ $user->email }}</div>
                                </td>

                                <td class="p-3.5">
                                    @if($user->role === 'admin')
                                        <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-800">Admin</span>
                                    @elseif($user->role === 'guru_bk')
                                        <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-800">Guru BK</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-800">Siswa</span>
                                    @endif
                                </td>

                                <td class="p-3.5 text-slate-600">
                                    @if($user->role === 'siswa')
                                        <span>NIS: {{ $user->nis ?? '-' }}</span> &bull; <span>Kelas: {{ $user->kelas ?? '-' }}</span>
                                    @elseif($user->role === 'guru_bk')
                                        <span>NIP: {{ $user->nip ?? '-' }}</span> &bull; <span>{{ $user->spesialisasi_kategori ?? 'Umum' }}</span>
                                    @else
                                        <span>Akses Sistem</span>
                                    @endif
                                </td>

                                <td class="p-3.5 text-slate-500 font-mono">
                                    {{ $user->no_hp ?? '-' }}
                                </td>

                                <td class="p-3.5">
                                    @if($user->role === 'guru_bk')
                                        <form action="{{ route('admin.users.toggle-ready', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-0.5 rounded-full text-[11px] font-medium border transition {{ $user->is_ready ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                                {{ $user->is_ready ? 'Ready' : 'Off' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-slate-400">Aktif</span>
                                    @endif
                                </td>

                                <td class="p-3.5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1">
                                        <button 
                                            type="button" 
                                            @click="openEditModal({{ json_encode($user) }})" 
                                            class="px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                                            Edit
                                        </button>

                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" onsubmit="return confirm('Hapus akun {{ addslashes($user->name) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-2.5 py-1 rounded-lg text-xs font-medium bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-slate-400">Tidak ada data pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>

        <!-- Modal Tambah / Edit Pengguna -->
        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div @click="modalOpen = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-slate-900" x-text="isEdit ? 'Edit Akun Pengguna' : 'Tambah Pengguna Baru'"></h3>
                        <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-base">✕</button>
                    </div>

                    <form :action="formAction" method="POST" class="space-y-3.5">
                        @csrf
                        <template x-if="isEdit">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Peran Akun *</label>
                            <select name="role" x-model="formData.role" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-slate-900 font-medium" required>
                                <option value="siswa">Siswa</option>
                                <option value="guru_bk">Guru BK</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                                <input type="text" name="name" maxlength="25" x-model="formData.name" placeholder="Muhammad Rizky" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-2xs transition" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Email *</label>
                                <input type="email" name="email" maxlength="50" x-model="formData.email" placeholder="siswa@sekolah.sch.id" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-2xs transition" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Kata Sandi <span x-show="isEdit" class="text-[10px] text-slate-400 font-normal">(Kosongkan jika tidak diubah)</span>
                            </label>
                            <input type="password" name="password" maxlength="15" placeholder="••••••••" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-2xs transition" :required="!isEdit">
                        </div>

                        <!-- Siswa fields -->
                        <div x-show="formData.role === 'siswa'" class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-2.5">
                            <p class="text-[11px] font-bold text-slate-700 uppercase tracking-wider">Identitas Siswa</p>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[10px] font-medium text-slate-500 mb-0.5">NIS</label>
                                    <input type="text" name="nis" maxlength="7" x-model="formData.nis" placeholder="2026100" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 shadow-2xs transition">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-slate-500 mb-0.5">Kelas</label>
                                    <input type="text" name="kelas" maxlength="10" x-model="formData.kelas" placeholder="XII RPL 1" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 shadow-2xs transition">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-slate-500 mb-0.5">Jurusan</label>
                                <input type="text" name="jurusan" maxlength="7" x-model="formData.jurusan" placeholder="RPL/TKJ" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 shadow-2xs transition">
                            </div>
                        </div>

                        <!-- Guru BK fields -->
                        <div x-show="formData.role === 'guru_bk'" class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-2.5">
                            <p class="text-[11px] font-bold text-slate-700 uppercase tracking-wider">Identitas Guru BK</p>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[10px] font-medium text-slate-500 mb-0.5">NIP</label>
                                    <input type="text" name="nip" maxlength="50" x-model="formData.nip" placeholder="198001012005011001" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 shadow-2xs transition">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-medium text-slate-500 mb-0.5">Status Ready</label>
                                    <select name="is_ready" x-model="formData.is_ready" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs font-semibold focus:ring-2 focus:ring-slate-900 shadow-2xs transition">
                                        <option :value="1">Ready</option>
                                        <option :value="0">Not Ready</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-medium text-slate-500 mb-0.5">Spesialisasi</label>
                                <input type="text" name="spesialisasi_kategori" x-model="formData.spesialisasi_kategori" placeholder="Belajar, Karier, Masa Depan" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 shadow-2xs transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">No. WhatsApp / HP</label>
                            <input type="text" name="no_hp" maxlength="14" x-model="formData.no_hp" placeholder="081234567890" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs focus:ring-2 focus:ring-slate-900 focus:border-slate-900 shadow-2xs transition">
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                            <button @click="modalOpen = false" type="button" class="px-4 py-2 rounded-xl text-xs font-medium text-slate-600 hover:bg-slate-100 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 transition">
                                <span x-text="isEdit ? 'Simpan Perubahan' : 'Buat Pengguna'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        function userManagement() {
            return {
                modalOpen: false,
                isEdit: false,
                formAction: '{{ route('admin.users.store') }}',
                formData: {
                    name: '',
                    email: '',
                    role: 'siswa',
                    nis: '',
                    kelas: '',
                    jurusan: '',
                    nip: '',
                    spesialisasi_kategori: '',
                    is_ready: 1,
                    no_hp: '',
                },
                openCreateModal() {
                    this.isEdit = false;
                    this.formAction = '{{ route('admin.users.store') }}';
                    this.formData = {
                        name: '',
                        email: '',
                        role: 'siswa',
                        nis: '',
                        kelas: '',
                        jurusan: '',
                        nip: '',
                        spesialisasi_kategori: '',
                        is_ready: 1,
                        no_hp: '',
                    };
                    this.modalOpen = true;
                },
                openEditModal(user) {
                    this.isEdit = true;
                    this.formAction = '{{ url('admin/users') }}/' + user.id;
                    this.formData = {
                        name: user.name || '',
                        email: user.email || '',
                        role: user.role || 'siswa',
                        nis: user.nis || '',
                        kelas: user.kelas || '',
                        jurusan: user.jurusan || '',
                        nip: user.nip || '',
                        spesialisasi_kategori: user.spesialisasi_kategori || '',
                        is_ready: user.is_ready ? 1 : 0,
                        no_hp: user.no_hp || '',
                    };
                    this.modalOpen = true;
                }
            }
        }
    </script>
</x-app-layout>
