<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block mb-1">Pengaturan Sistem</span>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Konfigurasi AI & Informasi Server</h1>
                <p class="text-xs text-slate-500 mt-0.5">Status integrasi NLP Pra-Konseling, environment, dan konfigurasi aplikasi.</p>
            </div>
        </div>

        <!-- AI Engine Status Cards -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-2xs space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Integrasi Mesin Klasifikasi AI (NLP)</h2>
                    <p class="text-xs text-slate-500">Status penyedia layanan pemrosesan bahasa alami</p>
                </div>
                <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-800 border border-slate-200">
                    Provider: {{ strtoupper($aiProvider) }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <!-- OpenAI Box -->
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-xs text-slate-900">OpenAI API</span>
                        @if($openaiConfigured)
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800">Tersedia</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-200 text-slate-600">Belum Terisi</span>
                        @endif
                    </div>
                    <p class="text-[11px] text-slate-500">Model: <code>gpt-4o-mini</code></p>
                </div>

                <!-- Gemini Box -->
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-xs text-slate-900">Google Gemini API</span>
                        @if($geminiConfigured)
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800">Tersedia</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-200 text-slate-600">Belum Terisi</span>
                        @endif
                    </div>
                    <p class="text-[11px] text-slate-500">Model: <code>gemini-1.5-flash</code></p>
                </div>

                <!-- Fallback NLP Engine -->
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50 space-y-1.5">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-xs text-slate-900">Smart NLP Fallback</span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-100 text-blue-800">Aktif (Offline)</span>
                    </div>
                    <p class="text-[11px] text-slate-500">Klasifikasi otomatis saat API key belum diisi.</p>
                </div>
            </div>

            <div class="p-4 rounded-xl bg-slate-900 text-slate-100 text-xs space-y-2 font-mono">
                <p class="text-slate-300 font-bold font-sans">Konfigurasi API Key di .env:</p>
                <div class="bg-black/40 p-3 rounded-lg text-[11px] text-slate-300">
                    AI_PROVIDER=openai<br>
                    OPENAI_API_KEY=sk-proj-...<br>
                    # atau<br>
                    # AI_PROVIDER=gemini<br>
                    # GEMINI_API_KEY=AIzaSy...
                </div>
            </div>
        </div>

        <!-- System Information -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-2xs space-y-4">
            <h2 class="text-sm font-bold text-slate-900">Informasi Lingkungan Server</h2>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-slate-500 font-medium block text-[10px] uppercase">Framework</span>
                    <span class="font-bold text-slate-900 text-xs">Laravel {{ app()->version() }}</span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-slate-500 font-medium block text-[10px] uppercase">PHP Version</span>
                    <span class="font-bold text-slate-900 text-xs">PHP {{ phpversion() }}</span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-slate-500 font-medium block text-[10px] uppercase">Database</span>
                    <span class="font-bold text-slate-900 text-xs">MySQL (temanbk)</span>
                </div>
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-slate-500 font-medium block text-[10px] uppercase">Environment</span>
                    <span class="font-bold text-slate-900 text-xs">{{ app()->environment() }}</span>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
