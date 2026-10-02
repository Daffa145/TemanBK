<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'TemanBK') }} - Masuk / Registrasi</title>

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

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6">
    <!-- Clean Minimal Header Logo -->
    <div class="mb-8 text-center">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-3 group">
            <img src="{{ asset('img/logo.png') }}" alt="TemanBK Logo" class="h-10 w-auto object-contain">
            <div class="text-left">
                <span class="text-xl font-bold tracking-tight text-slate-900 block leading-none">Teman<span class="text-brand-600">BK</span></span>
                <span class="text-[10px] font-medium text-slate-500 tracking-wider block uppercase mt-1">E-Counseling System</span>
            </div>
        </a>
    </div>

    <!-- Clean Container Box -->
    <div class="w-full sm:max-w-md bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
        {{ $slot }}
    </div>

    <p class="mt-6 text-xs text-slate-500 text-center">
        <a href="{{ url('/') }}" class="hover:text-slate-800 font-medium transition">← Kembali ke Halaman Utama</a>
    </p>
</body>
</html>
