<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'TemanBK' }} - Sistem Bimbingan Konseling Digital</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f4fe',
                            100: '#e1e9fd',
                            200: '#c7d6fc',
                            300: '#a3bdf9',
                            400: '#7599f5',
                            500: '#4e75ef',
                            600: '#3554e2',
                            700: '#2a41cc',
                            800: '#2737a5',
                            900: '#243282',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col selection:bg-slate-900 selection:text-white">
    
    <!-- Clean Minimal Top Navigation -->
    <nav class="bg-white border-b border-slate-200/80 sticky top-0 z-40" x-data="{ mobileOpen: false, userMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Nav Links -->
                <div class="flex items-center gap-8">
                    <a href="{{ Auth::check() ? (Auth::user()->isAdmin() ? route('admin.dashboard') : (Auth::user()->isGuruBk() ? route('counselor.dashboard') : route('student.dashboard'))) : url('/') }}" class="flex items-center gap-2.5">
                        <img src="{{ asset('img/logo.png') }}" alt="TemanBK Logo" class="h-8 w-auto object-contain">
                        <div class="flex flex-col">
                            <span class="text-base font-bold tracking-tight text-slate-900 leading-tight">Teman<span class="text-brand-600">BK</span></span>
                            <span class="text-[10px] font-medium text-slate-500 uppercase tracking-wider">E-Counseling</span>
                        </div>
                    </a>

                    <!-- Nav Links -->
                    @auth
                        <div class="hidden md:flex md:items-center md:gap-1">
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                    Ringkasan
                                </a>
                                <a href="{{ route('admin.users.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.users.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                    Pengguna
                                </a>
                                <a href="{{ route('admin.categories.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.categories.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                    Kategori
                                </a>
                                <a href="{{ route('admin.sessions.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.sessions.*') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                    Sesi Konseling
                                </a>
                                <a href="{{ route('admin.settings') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.settings') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                    Sistem & AI
                                </a>
                            @elseif(Auth::user()->isSiswa())
                                <a href="{{ route('student.dashboard') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('student.dashboard') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('student.ai-consult.form') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('student.ai-consult*') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                    Pra-Konseling
                                </a>
                            @elseif(Auth::user()->isGuruBk())
                                <a href="{{ route('counselor.dashboard') }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('counselor.dashboard') ? 'bg-slate-100 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                                    Dashboard Konselor
                                </a>
                            @endif
                        </div>
                    @endauth
                </div>

                <!-- Right Side: User Profile & Actions -->
                <div class="flex items-center gap-3">
                    @auth
                        <!-- Clean Role Badge -->
                        @if(Auth::user()->isAdmin())
                            <span class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                Administrator
                            </span>
                        @elseif(Auth::user()->isGuruBk())
                            <span class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                <span class="h-1.5 w-1.5 rounded-full {{ Auth::user()->is_ready ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                Guru BK
                            </span>
                        @elseif(Auth::user()->isSiswa())
                            <span class="hidden sm:inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                Siswa ({{ Auth::user()->kelas ?? 'Aktif' }})
                            </span>
                        @endif

                        <!-- User Dropdown Menu -->
                        <div class="relative" @click.away="userMenu = false">
                            <button @click="userMenu = !userMenu" type="button" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-slate-100 transition text-left focus:outline-none">
                                <div class="h-8 w-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-semibold text-xs border border-slate-200">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="hidden lg:block">
                                    <span class="text-xs font-semibold text-slate-800 block leading-tight">{{ Str::limit(Auth::user()->name, 18) }}</span>
                                    <span class="text-[10px] text-slate-500 block">{{ Auth::user()->email }}</span>
                                </div>
                                <svg class="w-3.5 h-3.5 text-slate-400 hidden lg:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <!-- Dropdown Box -->
                            <div x-show="userMenu" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 rounded-xl bg-white shadow-lg border border-slate-200 py-1.5 z-50">
                                <div class="px-3.5 py-2 border-b border-slate-100">
                                    <p class="text-[11px] text-slate-500">Masuk sebagai</p>
                                    <p class="text-xs font-semibold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="block px-3.5 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                    Pengaturan Profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-3.5 py-2 text-xs font-medium text-rose-600 hover:bg-rose-50">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-1.5 text-xs font-semibold text-slate-700 hover:text-slate-900 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 transition">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Global Toast Alerts -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 w-full">
        @if(session('success'))
            <div class="flex items-center gap-3 p-3.5 rounded-xl bg-emerald-50/80 border border-emerald-200 text-emerald-800 text-xs font-medium shadow-xs" x-data="{ show: true }" x-show="show">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <div class="flex-1">{{ session('success') }}</div>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 text-xs font-bold">✕</button>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center gap-3 p-3.5 rounded-xl bg-rose-50/80 border border-rose-200 text-rose-800 text-xs font-medium shadow-xs" x-data="{ show: true }" x-show="show">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <div class="flex-1">{{ session('error') }}</div>
                <button @click="show = false" class="text-rose-500 hover:text-rose-700 text-xs font-bold">✕</button>
            </div>
        @endif

        @if(session('info'))
            <div class="flex items-center gap-3 p-3.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-800 text-xs font-medium shadow-xs" x-data="{ show: true }" x-show="show">
                <svg class="w-4 h-4 text-slate-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="flex-1">{{ session('info') }}</div>
                <button @click="show = false" class="text-slate-500 hover:text-slate-700 text-xs font-bold">✕</button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Clean Footer -->
    <footer class="bg-white border-t border-slate-200/80 py-3.5 text-xs text-slate-500 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p>© {{ date('Y') }} TemanBK. Sistem Bimbingan Konseling Sekolah.</p>
            <p class="text-slate-400">Platform E-Counseling Terintegrasi</p>
        </div>
    </footer>
</body>
</html>
