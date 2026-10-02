<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-5" 
         x-data="{ 
            tab: '{{ $stats['pending'] > 0 ? 'pending' : ($stats['active'] > 0 ? 'active' : 'pending') }}', 
            completeModal: false, 
            selectedSessionId: null, 
            selectedStudentName: '' 
         }">
        
        <!-- Header & Status Toggle -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-2xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <span class="inline-block text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Portal Guru BK</span>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Halo, {{ $counselor->name }}</h1>
                <p class="text-xs text-slate-500 flex flex-wrap items-center gap-x-2">
                    <span>NIP: <strong class="font-semibold text-slate-700">{{ $counselor->nip ?? '-' }}</strong></span>
                    <span>&bull;</span>
                    <span>Spesialisasi: <strong class="font-semibold text-slate-700">{{ $counselor->spesialisasi_kategori ?? 'Umum' }}</strong></span>
                </p>
            </div>

            <!-- Toggle Ketersediaan Button (is_ready) -->
            <div class="shrink-0">
                <form action="{{ route('counselor.toggle-ready') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold text-xs border transition {{ $counselor->is_ready ? 'bg-emerald-50 text-emerald-800 border-emerald-300 hover:bg-emerald-100' : 'bg-slate-100 text-slate-700 border-slate-300 hover:bg-slate-200' }}">
                        <span class="h-2 w-2 rounded-full {{ $counselor->is_ready ? 'bg-emerald-600' : 'bg-slate-400' }}"></span>
                        <span>
                            {{ $counselor->is_ready ? 'Status: Siap Konseling (Ready)' : 'Status: Sedang Tidak Tersedia' }}
                        </span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Metric Cards & Interactive Tab Triggers -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
            <!-- Tab Trigger 1: Permintaan Masuk -->
            <button 
                type="button" 
                @click="tab = 'pending'"
                :class="tab === 'pending' ? 'ring-2 ring-slate-900 border-transparent bg-slate-900 text-white' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300'"
                class="rounded-2xl p-5 sm:p-6 border shadow-2xs text-left transition relative">
                <div class="flex items-center justify-between">
                    <span :class="tab === 'pending' ? 'text-slate-300' : 'text-slate-500'" class="text-[11px] font-semibold uppercase tracking-wider block">Permintaan Masuk</span>
                    @if($stats['pending'] > 0)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500 text-white animate-pulse">Baru</span>
                    @endif
                </div>
                <p class="text-2xl sm:text-3xl font-bold mt-2" :class="tab === 'pending' ? 'text-white' : 'text-slate-900'">{{ $stats['pending'] }}</p>
            </button>

            <!-- Tab Trigger 2: Sedang Berlangsung -->
            <button 
                type="button" 
                @click="tab = 'active'"
                :class="tab === 'active' ? 'ring-2 ring-slate-900 border-transparent bg-slate-900 text-white' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300'"
                class="rounded-2xl p-5 sm:p-6 border shadow-2xs text-left transition relative">
                <div class="flex items-center justify-between">
                    <span :class="tab === 'active' ? 'text-slate-300' : 'text-slate-500'" class="text-[11px] font-semibold uppercase tracking-wider block">Sedang Berlangsung</span>
                    @if($stats['active'] > 0)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-500 text-white">Aktif</span>
                    @endif
                </div>
                <p class="text-2xl sm:text-3xl font-bold mt-2" :class="tab === 'active' ? 'text-white' : 'text-slate-900'">{{ $stats['active'] }}</p>
            </button>

            <!-- Tab Trigger 3: Total Selesai -->
            <button 
                type="button" 
                @click="tab = 'completed'"
                :class="tab === 'completed' ? 'ring-2 ring-slate-900 border-transparent bg-slate-900 text-white' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300'"
                class="rounded-2xl p-5 sm:p-6 border shadow-2xs text-left transition relative">
                <div class="flex items-center justify-between">
                    <span :class="tab === 'completed' ? 'text-slate-300' : 'text-slate-500'" class="text-[11px] font-semibold uppercase tracking-wider block">Riwayat Selesai</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800" :class="tab === 'completed' ? 'bg-emerald-500/20 text-emerald-300' : ''">Arsip</span>
                </div>
                <p class="text-2xl sm:text-3xl font-bold mt-2" :class="tab === 'completed' ? 'text-white' : 'text-slate-900'">{{ $stats['completed'] }}</p>
            </button>
        </div>

        <!-- Main Content Area with Tabbed Panels -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
            
            <!-- Tab 1: Permintaan Masuk (Pending) -->
            <div x-show="tab === 'pending'" x-cloak>
                <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h2 class="text-xs sm:text-sm font-bold text-slate-900">Permintaan Konseling Masuk</h2>
                        <p class="text-[11px] text-slate-500">Permintaan bimbingan yang diajukan siswa untuk Anda</p>
                    </div>
                    @if($pendingSessions->count() > 0)
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-slate-900 text-white">
                            {{ $pendingSessions->count() }} Permintaan
                        </span>
                    @endif
                </div>

                <div class="divide-y divide-slate-100 max-h-[620px] overflow-y-auto">
                    @forelse($pendingSessions as $session)
                        <div class="p-4 sm:p-5 hover:bg-slate-50/50 transition space-y-3" x-data="{ expanded: false }">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-xs sm:text-sm font-bold text-slate-900">{{ $session->student->name }}</h3>
                                        <span class="px-2 py-0.5 rounded text-[11px] bg-slate-100 text-slate-700">
                                            {{ $session->student->kelas ?? 'Kelas -' }}
                                        </span>
                                        <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            {{ $session->category->name ?? 'Umum' }}
                                        </span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-0.5">
                                        NIS: {{ $session->student->nis ?? '-' }} &bull; Kontak: {{ $session->student->no_hp ?? '-' }} &bull; Diajukan: {{ $session->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-2">
                                    <form action="{{ route('counselor.sessions.accept', $session->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3.5 py-1.5 rounded-xl font-semibold text-xs text-white bg-slate-900 hover:bg-slate-800 transition shadow-2xs">
                                            Terima Konseling
                                        </button>
                                    </form>
                                    <form action="{{ route('counselor.sessions.reject', $session->id) }}" method="POST" onsubmit="return confirm('Tolak permohonan konseling ini?')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-xl font-semibold text-xs text-rose-600 hover:bg-rose-50 border border-rose-200 transition">
                                            Tolak
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Ringkasan AI -->
                            <div class="bg-slate-50 rounded-xl p-3 border border-slate-200 text-xs space-y-1">
                                <span class="font-semibold text-slate-900 block text-[11px]">Ringkasan Masalah:</span>
                                <p class="text-slate-700 leading-relaxed text-xs">{{ $session->ai_summary }}</p>
                            </div>

                            <div>
                                <button @click="expanded = !expanded" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1">
                                    <span x-text="expanded ? 'Tutup Curhatan Lengkap' : 'Lihat Curhatan Lengkap Siswa'"></span>
                                </button>
                                <div x-show="expanded" x-cloak class="mt-2 p-3.5 rounded-xl bg-slate-50 text-xs text-slate-700 leading-relaxed whitespace-pre-line border border-slate-200">
                                    {{ $session->original_complaint }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400 text-xs">
                            <p class="font-medium text-slate-500">Tidak ada permintaan konseling yang menunggu.</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Permintaan baru dari siswa akan muncul di sini secara otomatis.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tab 2: Sesi Sedang Berlangsung (Active) -->
            <div x-show="tab === 'active'" x-cloak>
                <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h2 class="text-xs sm:text-sm font-bold text-slate-900">Sesi Bimbingan yang Sedang Berlangsung</h2>
                        <p class="text-[11px] text-slate-500">Sesi yang telah Anda terima dan siap untuk dicatat solusinya</p>
                    </div>
                    @if($activeSessions->count() > 0)
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-600 text-white">
                            {{ $activeSessions->count() }} Sesi Aktif
                        </span>
                    @endif
                </div>

                <div class="divide-y divide-slate-100 max-h-[480px] overflow-y-auto">
                    @forelse($activeSessions as $active)
                        <div class="p-4 sm:p-5 hover:bg-slate-50/50 transition flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-xs sm:text-sm font-bold text-slate-900">{{ $active->student->name }}</h3>
                                    <span class="px-2 py-0.5 rounded text-[11px] bg-slate-100 text-slate-700">{{ $active->student->kelas ?? '-' }}</span>
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-700">{{ $active->category->name ?? '-' }}</span>
                                </div>
                                <p class="text-[11px] text-slate-500">
                                    Diterima pada: {{ $active->updated_at->format('d M Y, H:i') }} WIB &bull; Kontak: {{ $active->student->no_hp ?? '-' }}
                                </p>
                                <p class="text-xs text-slate-700 mt-1">
                                    <strong>Ringkasan:</strong> {{ $active->ai_summary }}
                                </p>
                            </div>

                            <div class="shrink-0">
                                <button 
                                    @click="completeModal = true; selectedSessionId = {{ $active->id }}; selectedStudentName = '{{ addslashes($active->student->name) }}'"
                                    type="button" 
                                    class="px-4 py-2 rounded-xl font-semibold text-xs text-white bg-emerald-700 hover:bg-emerald-800 transition shadow-2xs">
                                    Catat Hasil & Selesaikan
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400 text-xs">
                            <p class="font-medium text-slate-500">Tidak ada sesi yang sedang berlangsung.</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Sesi yang Anda terima dari tab Permintaan Masuk akan aktif di sini.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tab 3: Riwayat Selesai (Completed) -->
            <div x-show="tab === 'completed'" x-cloak>
                <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h2 class="text-xs sm:text-sm font-bold text-slate-900">Riwayat Sesi yang Telah Selesai</h2>
                        <p class="text-[11px] text-slate-500">Catatan hasil bimbingan dan tindak lanjut siswa</p>
                    </div>
                </div>

                <div class="divide-y divide-slate-100 max-h-[440px] overflow-y-auto">
                    @forelse($completedSessions as $comp)
                        <div class="p-4 sm:p-5 hover:bg-slate-50/50 transition space-y-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-900 text-xs sm:text-sm">{{ $comp->student->name }}</span>
                                    <span class="text-xs text-slate-500">({{ $comp->student->kelas ?? '-' }})</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">Selesai</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] bg-slate-100 text-slate-600">{{ $comp->category->name ?? 'Umum' }}</span>
                                </div>
                                <span class="text-[11px] text-slate-400">{{ $comp->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-xs text-slate-700">
                                <strong>Catatan / Solusi Guru BK:</strong> {{ $comp->counselor_notes }}
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400 text-xs">
                            <p class="font-medium text-slate-500">Belum ada riwayat sesi yang diselesaikan.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Modal Catatan Selesai Sesi -->
        <div x-show="completeModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div @click="completeModal = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-slate-900">
                            Selesaikan Sesi Konseling
                        </h3>
                        <button @click="completeModal = false" class="text-slate-400 hover:text-slate-600 font-bold text-base">✕</button>
                    </div>

                    <p class="text-xs text-slate-600 mb-4">
                        Siswa: <strong class="text-slate-900" x-text="selectedStudentName"></strong>. Masukkan arahan, rekomendasi, atau catatan hasil konsultasi.
                    </p>

                    <form :action="'{{ url('counselor/sessions') }}/' + selectedSessionId + '/complete'" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="counselor_notes" class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Catatan Hasil Konseling *
                            </label>
                            <textarea 
                                id="counselor_notes" 
                                name="counselor_notes" 
                                rows="5" 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-xs text-slate-900 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition shadow-2xs"
                                placeholder="Masukkan catatan hasil bimbingan siswa di sini..."
                                required></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                            <button @click="completeModal = false" type="button" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 transition">
                                Simpan & Selesaikan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
