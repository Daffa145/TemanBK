<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TemanBK - Platform Bimbingan Konseling Sekolah Modern & Terpercaya</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-glow {
            background: radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.12) 0%, rgba(255, 255, 255, 0) 70%);
        }
        .grid-pattern {
            background-size: 32px 32px;
            background-image: 
                linear-gradient(to right, rgba(226, 232, 240, 0.4) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(226, 232, 240, 0.4) 1px, transparent 1px);
        }
    </style>
</head>
<body class="bg-white text-slate-800 font-sans antialiased selection:bg-brand-600 selection:text-white">

    <!-- Top Navigation Bar -->
    <header class="border-b border-slate-100/80 sticky top-0 bg-white/90 backdrop-blur-md z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-18 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <div class="p-1.5 rounded-xl bg-brand-50 border border-brand-100 group-hover:scale-105 transition">
                    <img src="{{ asset('img/logo.png') }}" alt="TemanBK Logo" class="h-8 w-auto object-contain">
                </div>
                <div>
                    <span class="text-lg font-bold tracking-tight text-slate-900 block leading-tight">Teman<span class="text-brand-600">BK</span></span>
                    <span class="text-[10px] font-medium text-slate-400 tracking-wider block uppercase">Layanan Konseling Digital</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-8 text-xs font-semibold text-slate-600">
                <a href="#fitur" class="hover:text-brand-600 transition">Fitur Utama</a>
                <a href="#alur" class="hover:text-brand-600 transition">Alur Bimbingan</a>
                <a href="#layanan" class="hover:text-brand-600 transition">Bidang Layanan</a>
            </nav>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 shadow-sm hover:shadow transition">
                            <span>Buka Dashboard</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2.5 text-xs font-semibold text-slate-700 hover:text-slate-900 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-brand-600 hover:bg-brand-700 shadow-sm hover:shadow transition">
                            <span>Daftar Siswa</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="relative pt-16 pb-20 lg:pt-24 lg:pb-28 overflow-hidden gradient-glow grid-pattern border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white border border-brand-200 shadow-xs text-brand-700 mb-8 animate-fade-in">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-600"></span>
                    </span>
                    <span>Layanan Bimbingan Konseling Digital Sekolah</span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-slate-900 max-w-4xl mx-auto leading-[1.15]">
                    Konsultasi Bimbingan Siswa Lebih <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-600 via-indigo-600 to-indigo-800">Terarah & Nyaman</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-slate-600 text-base sm:text-lg max-w-2xl mx-auto mt-6 leading-relaxed">
                    Wadah terpercaya bagi siswa untuk menyampaikan kendala belajar, karier, dan masa depan dengan aman serta terhubung langsung ke Guru BK yang tepat.
                </p>

                <!-- Action CTAs -->
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3.5">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 shadow-md shadow-brand-500/20 hover:shadow-lg transition">
                        <span>Mulai Konsultasi Siswa</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>

                <!-- Live Interface Mockup Preview -->
                <div class="mt-14 max-w-4xl mx-auto">
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xl shadow-slate-200/60 p-4 sm:p-6 text-left">
                        <!-- Browser-like header dots -->
                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-rose-400"></span>
                                <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                                <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                                <span class="text-[11px] font-mono text-slate-400 ml-2">temanbk.sch.id &bull; Pra-Konseling & Rekomendasi</span>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Sistem Aktif
                            </span>
                        </div>

                        <!-- Mockup Grid Content -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Left: Siswa Input & AI Triage -->
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/70 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">1. Analisis Keluhan Siswa</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-brand-100 text-brand-700">Triage Cerdas</span>
                                </div>
                                <div class="p-3 bg-white rounded-lg border border-slate-200 text-xs text-slate-700 italic">
                                    "Saya merasa cemas dan ragu memilih jurusan kuliah yang sesuai dengan minat dan kemampuan saya..."
                                </div>
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="text-slate-500 text-[11px]">Kategori Terdeteksi:</span>
                                    <span class="px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 border border-purple-200 text-[11px] font-bold">Masa Depan & Karier</span>
                                </div>
                            </div>

                            <!-- Right: Recommended Guru BK Match -->
                            <div class="p-4 rounded-xl bg-brand-50/50 border border-brand-200/70 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold text-brand-800 uppercase tracking-wider">2. Rekomendasi Guru BK</span>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Siap Konseling</span>
                                </div>
                                <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-brand-100 shadow-2xs">
                                    <div class="w-10 h-10 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-sm shrink-0">
                                        BK
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-xs font-bold text-slate-900 truncate">Dra. Siti Rahmawati, M.Pd</h4>
                                        <p class="text-[11px] text-slate-500">Spesialis Masa Depan & Perguruan Tinggi</p>
                                    </div>
                                </div>
                                <div class="text-[11px] text-brand-700 flex items-center gap-1.5 font-medium">
                                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Tersedia untuk sesi tatap muka & catatan bimbingan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- Stats / Trust Points -->
        <section class="py-10 bg-slate-50 border-b border-slate-200/80">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">
                    <div class="space-y-1">
                        <div class="text-2xl sm:text-3xl font-extrabold text-slate-900">100%</div>
                        <div class="text-xs font-medium text-slate-500">Kerahasiaan Terjamin</div>
                    </div>
                    <div class="space-y-1">
                        <div class="text-2xl sm:text-3xl font-extrabold text-brand-600">4 Bidang</div>
                        <div class="text-xs font-medium text-slate-500">Layanan Bimbingan</div>
                    </div>
                    <div class="space-y-1">
                        <div class="text-2xl sm:text-3xl font-extrabold text-slate-900">AI Triage</div>
                        <div class="text-xs font-medium text-slate-500">Pra-Konseling Terarah</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Alur Konseling Section -->
        <section id="alur" class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-600 block mb-2">Langkah Mudah</span>
                <h2 class="text-3xl font-extrabold text-slate-900">Bagaimana <span class="text-brand-600">TemanBK</span> Bekerja?</h2>
                <p class="text-sm text-slate-600 mt-3">Proses bimbingan konseling yang terstruktur dan terintegrasi dari awal hingga sesi selesai.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative">
                <!-- Step 1 -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-2xs hover:shadow-md transition space-y-3 relative">
                    <div class="w-10 h-10 rounded-xl bg-brand-50 border border-brand-200 text-brand-700 flex items-center justify-center font-bold text-sm">
                        01
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Sampaikan Keluhan</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Siswa menuliskan masalah atau cerita yang sedang dialami secara rahasia dan nyaman.</p>
                </div>

                <!-- Step 2 -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-2xs hover:shadow-md transition space-y-3 relative">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 border border-purple-200 text-purple-700 flex items-center justify-center font-bold text-sm">
                        02
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Klasifikasi Kebutuhan</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Sistem menganalisis bidang bimbingan yang tepat (Belajar, Karier, Masa Depan, Sosial).</p>
                </div>

                <!-- Step 3 -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-2xs hover:shadow-md transition space-y-3 relative">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center justify-center font-bold text-sm">
                        03
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Pilih Guru BK</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Siswa memilih Guru BK yang tersedia sesuai dengan bidang spesialisasi yang dibutuhkan.</p>
                </div>

                <!-- Step 4 -->
                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-2xs hover:shadow-md transition space-y-3 relative">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 flex items-center justify-center font-bold text-sm">
                        04
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Bimbingan & Solusi</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Guru BK memproses sesi, memberikan arahan solusi, dan mencatat rekam konseling.</p>
                </div>
            </div>
        </section>

        <!-- 4 Bidang Layanan Section -->
        <section id="layanan" class="py-20 bg-slate-50 border-t border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-14">
                    <span class="text-xs font-bold uppercase tracking-wider text-brand-600 block mb-2">Klasifikasi Konseling</span>
                    <h2 class="text-3xl font-extrabold text-slate-900">4 Bidang Layanan Bimbingan</h2>
                    <p class="text-sm text-slate-600 mt-3">Membantu siswa menemukan solusi pada setiap fase perkembangan sekolah.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <!-- Belajar -->
                    <div class="p-6 rounded-2xl bg-white border border-slate-200 hover:border-brand-300 transition shadow-2xs space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <h3 class="font-bold text-slate-900 text-base">Belajar</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">Manajemen waktu, motivasi belajar, kesulitan memahami pelajaran, dan persiapan ujian akademik.</p>
                    </div>

                    <!-- Karier -->
                    <div class="p-6 rounded-2xl bg-white border border-slate-200 hover:border-brand-300 transition shadow-2xs space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="font-bold text-slate-900 text-base">Karier</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">Pemetaan keahlian, persiapan magang/PKL, dan kesiapan memasuki dunia kerja profesional.</p>
                    </div>

                    <!-- Masa Depan -->
                    <div class="p-6 rounded-2xl bg-white border border-slate-200 hover:border-brand-300 transition shadow-2xs space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <h3 class="font-bold text-slate-900 text-base">Masa Depan</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">Perencanaan studi lanjutan, seleksi masuk perguruan tinggi (SNBP/SNBT), dan info beasiswa.</p>
                    </div>

                    <!-- Sosial & Pribadi -->
                    <div class="p-6 rounded-2xl bg-white border border-slate-200 hover:border-brand-300 transition shadow-2xs space-y-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <h3 class="font-bold text-slate-900 text-base">Sosial & Pribadi</h3>
                        <p class="text-xs text-slate-600 leading-relaxed">Penyesuaian lingkungan sekolah, hubungan pertemanan, dan komunikasi dengan keluarga.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Fitur Section -->
        <section id="fitur" class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-600 block mb-2">Keunggulan Sistem</span>
                <h2 class="text-3xl font-extrabold text-slate-900">Didesain Khusus untuk Ekosistem Sekolah</h2>
                <p class="text-sm text-slate-600 mt-3">Mengintegrasikan kebutuhan siswa, beban kerja Guru BK, dan pengawasan administrator.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-2xs space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base">Privasi & Kerahasiaan Aman</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Setiap sesi bimbingan hanya dapat diakses oleh siswa bersangkutan dan Guru BK yang menangani.</p>
                </div>

                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-2xs space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base">Rekam Bimbingan Terstruktur</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Catatan perkembangan dan rekomendasi bimbingan tersimpan rapi untuk pemantauan berkelanjutan.</p>
                </div>

                <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-2xs space-y-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-base">Dashboard Admin Lengkap</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">Pengelolaan akun pengguna, kategori bimbingan, konfigurasi sistem AI, dan monitoring reassign sesi.</p>
                </div>
            </div>
        </section>

        <!-- Final CTA Banner -->
        <section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="p-8 sm:p-12 rounded-3xl bg-gradient-to-r from-brand-700 via-brand-600 to-indigo-700 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl">
                <div class="max-w-xl text-center md:text-left">
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Siap Memulai Bimbingan Konseling?</h2>
                    <p class="text-brand-100 text-xs sm:text-sm mt-2 leading-relaxed">
                        Daftarkan akun siswa Anda sekarang untuk mendapatkan bimbingan terarah dan terpercaya bersama Guru BK.
                    </p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('register') }}" class="px-6 py-3.5 rounded-xl font-bold text-xs sm:text-sm text-brand-700 bg-white hover:bg-brand-50 shadow-md transition">
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}" class="px-6 py-3.5 rounded-xl font-bold text-xs sm:text-sm text-white bg-white/10 hover:bg-white/20 border border-white/20 transition">
                        Masuk Akun
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/80 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('img/logo.png') }}" alt="TemanBK Logo" class="h-7 w-auto object-contain">
                    <div>
                        <span class="text-sm font-bold text-slate-900 block leading-tight">Teman<span class="text-brand-600">BK</span></span>
                        <span class="text-[10px] text-slate-400 block">Sistem Bimbingan Konseling Digital</span>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-6 text-xs text-slate-500">
                    <a href="#fitur" class="hover:text-slate-900 transition">Fitur</a>
                    <a href="#alur" class="hover:text-slate-900 transition">Alur Konseling</a>
                    <a href="#layanan" class="hover:text-slate-900 transition">Bidang Layanan</a>
                    <a href="{{ route('login') }}" class="hover:text-slate-900 transition">Masuk</a>
                    <a href="{{ route('register') }}" class="hover:text-slate-900 transition">Daftar</a>
                </div>

                <div class="text-xs text-slate-400">
                    &copy; {{ date('Y') }} TemanBK. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
