<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6" x-data="sessionManagement()">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block mb-1">Monitoring
                    Konseling</span>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Seluruh Sesi Bimbingan Siswa
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Pantau jalannya bimbingan, alihkan guru bertugas, dan kelola
                    status sesi.</p>
            </div>
        </div>

        <!-- Filter Toolbar -->
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs">
            <form action="{{ route('admin.sessions.index') }}" method="GET"
                class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Status
                        Sesi</label>
                    <select name="status"
                        class="w-full px-3 py-1.5 rounded-xl border border-slate-300 text-xs font-medium">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Pending (Menunggu)
                        </option>
                        <option value="accepted" {{ $statusFilter === 'accepted' ? 'selected' : '' }}>Accepted (Berjalan)
                        </option>
                        <option value="completed" {{ $statusFilter === 'completed' ? 'selected' : '' }}>Completed
                            (Selesai)</option>
                        <option value="rejected" {{ $statusFilter === 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Kategori
                        Masalah</label>
                    <select name="category_id"
                        class="w-full px-3 py-1.5 rounded-xl border border-slate-300 text-xs font-medium">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $categoryFilter == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Guru BK
                        Bertugas</label>
                    <select name="counselor_id"
                        class="w-full px-3 py-1.5 rounded-xl border border-slate-300 text-xs font-medium">
                        <option value="">Semua Guru BK</option>
                        @foreach($counselors as $c)
                            <option value="{{ $c->id }}" {{ $counselorFilter == $c->id ? 'selected' : '' }}>{{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="w-full py-2 rounded-xl text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 transition">
                        Terapkan
                    </button>
                    <a href="{{ route('admin.sessions.index') }}"
                        class="px-3 py-2 rounded-xl text-xs font-medium bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Sessions List Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
            <div class="overflow-x-auto max-h-[330px] overflow-y-auto">
                <table class="w-full text-left text-xs">
                    <thead
                        class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold uppercase tracking-wider sticky top-0 z-10">
                        <tr>
                            <th class="p-3.5">ID & Tanggal</th>
                            <th class="p-3.5">Siswa</th>
                            <th class="p-3.5">Guru BK Bertugas</th>
                            <th class="p-3.5">Kategori & Ringkasan</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($sessions as $session)
                            <tr class="hover:bg-slate-50/50 transition" x-data="{ expanded: false }">
                                <td class="p-3.5 whitespace-nowrap text-slate-600">
                                    <span class="font-mono font-bold text-slate-900">#{{ $session->id }}</span> <br>
                                    <span
                                        class="text-[11px] text-slate-400">{{ $session->created_at->format('d/m/Y H:i') }}</span>
                                </td>

                                <td class="p-3.5">
                                    <div class="font-semibold text-slate-900">{{ $session->student->name }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $session->student->kelas ?? '-' }} &bull;
                                        NIS: {{ $session->student->nis ?? '-' }}</div>
                                </td>

                                <td class="p-3.5">
                                    <div class="font-medium text-slate-900">
                                        {{ $session->counselor->name ?? 'Belum Ditugaskan' }}</div>
                                    <div class="text-[11px] text-slate-500">NIP: {{ $session->counselor->nip ?? '-' }}</div>
                                </td>

                                <td class="p-3.5 max-w-sm">
                                    <span
                                        class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 mb-1 inline-block">
                                        {{ $session->category->name ?? 'Umum' }}
                                    </span>
                                    <p class="text-slate-700 text-xs line-clamp-2">
                                        {{ $session->ai_summary }}
                                    </p>

                                    <div class="mt-1">
                                        <button @click="expanded = !expanded"
                                            class="text-[11px] font-medium text-slate-600 hover:text-slate-900">
                                            <span x-text="expanded ? 'Tutup Detail' : 'Lihat Detail Masalah'"></span>
                                        </button>
                                        <div x-show="expanded" x-cloak
                                            class="mt-2 p-3 rounded-xl bg-slate-50 text-[11px] space-y-2 border border-slate-200">
                                            <div>
                                                <strong class="text-slate-900 block">Curhatan Siswa:</strong>
                                                <p class="text-slate-700 whitespace-pre-line">
                                                    {{ $session->original_complaint }}</p>
                                            </div>
                                            @if($session->counselor_notes)
                                                <div class="pt-2 border-t border-slate-200">
                                                    <strong class="text-slate-900 block">Catatan Guru BK:</strong>
                                                    <p class="text-slate-700 whitespace-pre-line">
                                                        {{ $session->counselor_notes }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="p-3.5 whitespace-nowrap">
                                    @if($session->status === 'pending')
                                        <span
                                            class="px-2 py-0.5 rounded text-[11px] font-medium bg-amber-50 text-amber-800 border border-amber-200">Pending</span>
                                    @elseif($session->status === 'accepted')
                                        <span
                                            class="px-2 py-0.5 rounded text-[11px] font-medium bg-blue-50 text-blue-800 border border-blue-200">Berjalan</span>
                                    @elseif($session->status === 'completed')
                                        <span
                                            class="px-2 py-0.5 rounded text-[11px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">Selesai</span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 rounded text-[11px] font-medium bg-rose-50 text-rose-800 border border-rose-200">Ditolak</span>
                                    @endif
                                </td>

                                <td class="p-3.5 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1">
                                        <button type="button" @click="openReassignModal({{ json_encode($session) }})"
                                            class="px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 hover:bg-slate-200 text-slate-700 transition"
                                            title="Alihkan ke Guru BK lain">
                                            Alihkan
                                        </button>

                                        <button type="button" @click="openStatusModal({{ json_encode($session) }})"
                                            class="px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                                            Status
                                        </button>

                                        <form action="{{ route('admin.sessions.delete', $session->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus sesi konseling #{{ $session->id }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-2.5 py-1 rounded-lg text-xs font-medium bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-slate-400">Tidak ada data sesi konseling.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-slate-100">
                {{ $sessions->links() }}
            </div>
        </div>

        <!-- Modal Alihkan Guru BK (Reassign) -->
        <div x-show="reassignModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog"
            aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div @click="reassignModalOpen = false"
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                <div
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-slate-900">
                            Alihkan Guru BK
                        </h3>
                        <button @click="reassignModalOpen = false"
                            class="text-slate-400 hover:text-slate-600 font-bold text-base">✕</button>
                    </div>

                    <p class="text-xs text-slate-600 mb-4">
                        Alihkan bimbingan siswa <strong class="text-slate-900"
                            x-text="activeSession.student ? activeSession.student.name : ''"></strong> ke Guru BK lain:
                    </p>

                    <form :action="'{{ url('admin/sessions') }}/' + activeSession.id + '/reassign'" method="POST"
                        class="space-y-3.5">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Guru BK Pengganti
                                *</label>
                            <select name="counselor_id"
                                class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-slate-900 font-medium"
                                required>
                                @foreach($counselors as $c)
                                    <option value="{{ $c->id }}">
                                        {{ $c->name }} ({{ $c->is_ready ? 'Ready' : 'Off' }}) -
                                        {{ $c->spesialisasi_kategori ?? 'Umum' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                            <button @click="reassignModalOpen = false" type="button"
                                class="px-4 py-2 rounded-xl text-xs font-medium text-slate-600 hover:bg-slate-100 transition">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 rounded-xl text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 transition">
                                Simpan Pengalihan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Ubah Status & Catatan -->
        <div x-show="statusModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog"
            aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div @click="statusModalOpen = false"
                    class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                <div
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-slate-900">
                            Ubah Status Sesi Konseling
                        </h3>
                        <button @click="statusModalOpen = false"
                            class="text-slate-400 hover:text-slate-600 font-bold text-base">✕</button>
                    </div>

                    <form :action="'{{ url('admin/sessions') }}/' + activeSession.id + '/status'" method="POST"
                        class="space-y-3.5">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Status Sesi *</label>
                            <select name="status" x-model="activeSession.status"
                                class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-slate-900 font-medium"
                                required>
                                <option value="pending">Pending (Menunggu)</option>
                                <option value="accepted">Accepted (Berjalan)</option>
                                <option value="completed">Completed (Selesai)</option>
                                <option value="rejected">Rejected (Ditolak)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan / Rekomendasi
                                Konseling</label>
                            <textarea name="counselor_notes" x-model="activeSession.counselor_notes" rows="4"
                                placeholder="Masukkan catatan hasil bimbingan..."
                                class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-slate-900"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                            <button @click="statusModalOpen = false" type="button"
                                class="px-4 py-2 rounded-xl text-xs font-medium text-slate-600 hover:bg-slate-100 transition">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 rounded-xl text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 transition">
                                Perbarui Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        function sessionManagement() {
            return {
                reassignModalOpen: false,
                statusModalOpen: false,
                activeSession: {},
                openReassignModal(session) {
                    this.activeSession = session;
                    this.reassignModalOpen = true;
                },
                openStatusModal(session) {
                    this.activeSession = session;
                    this.statusModalOpen = true;
                }
            }
        }
    </script>
</x-app-layout>