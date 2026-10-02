<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header Banner -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/90 shadow-2xs flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-1">
                <span class="inline-block text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Portal Layanan Siswa</span>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Halo, {{ $student->name }}</h1>
                <p class="text-slate-600 text-xs sm:text-sm max-w-xl">
                    Sampaikan keluhan atau hal yang ingin Anda konsultasikan terkait bidang akademik, karier, masa depan, maupun sosial secara terarah.
                </p>
                <div class="flex flex-wrap items-center gap-2 pt-2 text-xs text-slate-500">
                    <span class="bg-slate-100 px-2.5 py-1 rounded-md">NIS: {{ $student->nis ?? '-' }}</span>
                    <span class="bg-slate-100 px-2.5 py-1 rounded-md">Kelas: {{ $student->kelas ?? 'Umum' }}</span>
                    <span class="bg-slate-100 px-2.5 py-1 rounded-md">Jurusan: {{ $student->jurusan ?? '-' }}</span>
                </div>
            </div>

            <div class="shrink-0">
                <a href="{{ route('student.ai-consult.form') }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl font-semibold text-xs text-white bg-slate-900 hover:bg-slate-800 shadow-sm transition">
                    <span>Mulai Konsultasi Baru</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>

        <!-- Metric Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs">
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Sesi Sedang Berjalan</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $activeSessionsCount }}</p>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs">
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Selesai Dikonseling</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $completedSessionsCount }}</p>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs">
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Riwayat Konsultasi</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $recentSessions->count() }}</p>
            </div>
        </div>

        <!-- Riwayat Sesi Konseling -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Riwayat & Status Konsultasi</h2>
                    <p class="text-xs text-slate-500">Daftar permohonan bimbingan dan tindak lanjut dari Guru BK</p>
                </div>
            </div>

            <div class="divide-y divide-slate-100 max-h-[620px] overflow-y-auto">
                @forelse($recentSessions as $session)
                    <div class="p-5 hover:bg-slate-50/50 transition space-y-3" x-data="{ expanded: false }">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                @if($session->status === 'pending')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                        Menunggu Guru BK
                                    </span>
                                @elseif($session->status === 'accepted')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-800 border border-blue-200">
                                        Sedang Berjalan
                                    </span>
                                @elseif($session->status === 'completed')
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        Selesai
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                        Ditolak
                                    </span>
                                @endif

                                @if($session->category)
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-700">
                                        {{ $session->category->name }}
                                    </span>
                                @endif
                            </div>

                            <span class="text-xs text-slate-400">
                                {{ $session->created_at->format('d M Y, H:i') }} WIB
                            </span>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-slate-900">
                                Guru BK: <span class="font-bold text-slate-800">{{ $session->counselor->name ?? 'Belum Ditugaskan' }}</span>
                            </p>
                        </div>

                        @if($session->ai_summary)
                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-700">
                                <span class="font-semibold text-slate-900 block mb-0.5">Ringkasan Masalah:</span>
                                {{ $session->ai_summary }}
                            </div>
                        @endif

                        @if($session->counselor_notes)
                            <div class="bg-emerald-50/70 border border-emerald-200 rounded-xl p-3.5 text-xs text-emerald-950">
                                <span class="font-semibold text-emerald-900 block mb-0.5">Catatan / Rekomendasi Guru BK:</span>
                                <p class="whitespace-pre-line">{{ $session->counselor_notes }}</p>
                            </div>
                        @endif

                        <div>
                            <button @click="expanded = !expanded" class="text-xs font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1">
                                <span x-text="expanded ? 'Tutup Curhatan Asli' : 'Lihat Curhatan Asli'"></span>
                            </button>
                            <div x-show="expanded" x-cloak class="mt-2 p-3 bg-slate-50 rounded-xl text-xs text-slate-700 whitespace-pre-line border border-slate-200">
                                {{ $session->original_complaint }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400">
                        <p class="text-xs font-medium text-slate-600">Belum ada riwayat konsultasi.</p>
                        <a href="{{ route('student.ai-consult.form') }}" class="inline-block mt-3 px-4 py-2 rounded-lg text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 transition">
                            Mulai Konsultasi Baru
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
