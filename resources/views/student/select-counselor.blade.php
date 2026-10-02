<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 space-y-6">
        
        <!-- Step Breadcrumb -->
        <div class="flex items-center gap-2 text-xs text-slate-500 font-medium">
            <span class="text-emerald-700 font-semibold">1. Input Cerita</span>
            <span>&rsaquo;</span>
            <span class="text-slate-900 font-bold">2. Pilih Guru BK</span>
            <span>&rsaquo;</span>
            <span class="text-slate-400">3. Bimbingan Konseling</span>
        </div>

        <!-- Notifikasi Hasil Klasifikasi AI -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-2xs space-y-4" x-data="{ viewOriginal: false }">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-3.5">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-2.5 w-2.5 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-600"></span>
                    </span>
                    <div>
                        <h2 class="text-xs sm:text-sm font-bold text-slate-900">Notifikasi Kategori yang Ditentukan AI</h2>
                        <p class="text-[11px] text-slate-500">Keluhan Anda telah dianalisis & diklasifikasikan secara otomatis</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-500 font-medium">Kategori:</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-900 text-white shadow-2xs">
                        {{ $category->name }}
                    </span>
                </div>
            </div>

            <div class="space-y-1.5">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Ringkasan Analisis AI:</span>
                <p class="text-xs sm:text-sm text-slate-800 leading-relaxed font-medium bg-slate-50 p-4 rounded-xl border border-slate-200">
                    {{ $aiSummary }}
                </p>
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <button @click="viewOriginal = !viewOriginal" class="font-medium text-slate-600 hover:text-slate-900 flex items-center gap-1">
                    <span x-text="viewOriginal ? 'Sembunyikan keluhan asli' : 'Lihat keluhan lengkap yang ditulis'"></span>
                </button>

                <span class="text-[11px] text-slate-400">
                    Sistem: <strong class="text-slate-600">{{ session('ai_provider', 'Smart NLP Engine') }}</strong>
                </span>
            </div>

            <div x-show="viewOriginal" x-cloak class="p-3.5 rounded-xl bg-slate-50 text-xs text-slate-700 leading-relaxed border border-slate-200 whitespace-pre-line">
                {{ $originalComplaint }}
            </div>
        </div>

        <!-- Section Title -->
        <div class="flex items-center justify-between pt-2">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Pilih Guru Bimbingan Konseling</h2>
                <p class="text-xs text-slate-500">Pilih Guru BK yang sedang dalam status <strong>Ready</strong> untuk memulai konsultasi.</p>
            </div>

            <a href="{{ route('student.ai-consult.form') }}" class="text-xs font-medium text-slate-600 hover:text-slate-900">
                Ubah Cerita
            </a>
        </div>

        <!-- Daftar Guru BK Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($counselors as $counselor)
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs flex flex-col justify-between space-y-4">
                    <div class="space-y-2">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="font-bold text-slate-900 text-sm leading-tight">{{ $counselor->name }}</h3>
                                <p class="text-xs text-slate-500 mt-0.5">NIP: {{ $counselor->nip ?? '-' }}</p>
                            </div>
                            @if($counselor->is_ready)
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-center gap-1.5">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span> Ready
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-500 border border-slate-200 flex items-center gap-1.5">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Tidak Tersedia
                                </span>
                            @endif
                        </div>

                        <div class="text-xs text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                            <span class="font-semibold text-slate-700">Spesialisasi:</span> {{ $counselor->spesialisasi_kategori ?? 'Umum' }}
                        </div>
                    </div>

                    <form action="{{ route('student.sessions.create') }}" method="POST">
                        @csrf
                        <input type="hidden" name="counselor_id" value="{{ $counselor->id }}">

                        @if($counselor->is_ready)
                            <button type="submit" class="w-full py-2.5 rounded-xl font-semibold text-xs text-white bg-slate-900 hover:bg-slate-800 shadow-2xs transition">
                                Mulai Konsultasi Bersama Guru Ini
                            </button>
                        @else
                            <button type="button" disabled class="w-full py-2.5 rounded-xl font-medium text-xs text-slate-400 bg-slate-100 cursor-not-allowed">
                                Sedang Tidak Menerima Sesi
                            </button>
                        @endif
                    </form>
                </div>
            @empty
                <div class="col-span-2 p-8 text-center bg-white rounded-2xl border border-slate-200 text-xs text-slate-500">
                    Belum ada data Guru BK yang terdaftar.
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>
