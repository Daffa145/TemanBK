<x-app-layout>
    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6" x-data="aiConsultForm()">
        
        <!-- Header -->
        <div class="mb-6">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1">Tahap 1 dari 2</span>
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Ceritakan Hal yang Ingin Dikonsultasikan</h1>
            <p class="text-slate-600 text-xs sm:text-sm mt-1 leading-relaxed">
                Tuliskan kendala belajar, perencanaan karier, masa depan, atau persoalan sosial. AI akan otomatis mengidentifikasi kategori masalah dan mengarahkan ke Guru BK yang tepat.
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs p-6 sm:p-8 relative">
            
            <!-- Loading Overlay during AI analysis submission -->
            <div x-show="loading" x-cloak class="absolute inset-0 bg-white/95 backdrop-blur-xs rounded-2xl z-30 flex flex-col items-center justify-center p-6 text-center">
                <div class="h-10 w-10 rounded-full border-2 border-slate-200 border-t-slate-900 animate-spin mb-4"></div>
                <h3 class="text-sm font-bold text-slate-900 mb-1">AI Sedang Mengklasifikasikan Topik...</h3>
                <p class="text-xs text-slate-500 max-w-sm">Mencocokkan kategori dan rekomendasi Guru BK spesialis yang siap membimbing Anda.</p>
            </div>

            <form action="{{ route('student.ai-consult.analyze') }}" method="POST" @submit="handleSubmit($event)">
                @csrf

                <!-- Textarea -->
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="complaint" class="block text-xs font-semibold text-slate-700">
                            Keluhan atau Hal yang Ingin Dikonsultasikan *
                        </label>
                        <span class="text-[11px] text-slate-400">
                            <span x-text="textCount"></span> / 2500 karakter
                        </span>
                    </div>

                    <textarea 
                        id="complaint" 
                        name="complaint" 
                        x-model="complaintText"
                        @input="handleInput()"
                        rows="6" 
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 bg-white text-slate-900 text-xs sm:text-sm leading-relaxed placeholder-slate-400 focus:ring-2 focus:ring-slate-900 focus:border-slate-900 transition shadow-2xs"
                        placeholder="Tuliskan cerita Anda di sini secara bebas..."
                        required>{{ old('complaint') }}</textarea>

                    @error('complaint')
                        <p class="text-rose-600 text-xs font-medium mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Live AI Category Detection Notification Box -->
                <div x-show="aiResult.detected" x-cloak class="mb-5 p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-2.5 transition">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-600"></span>
                            </span>
                            <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider">
                                Notifikasi Kategori Topik AI
                            </span>
                        </div>
                        
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-900 text-white shadow-2xs" x-text="aiResult.category_name">
                        </span>
                    </div>

                    <div class="bg-white p-3 rounded-lg border border-slate-200/80 text-xs text-slate-700">
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block mb-0.5">Ringkasan Inti Permasalahan:</span>
                        <p class="leading-relaxed" x-text="aiResult.summary"></p>
                    </div>

                    <div class="flex items-center justify-between text-[11px] text-slate-400 pt-0.5">
                        <span>Mesin Analisis: <strong class="text-slate-600" x-text="aiResult.provider"></strong></span>
                        <span class="text-emerald-700 font-medium">✓ Siap diteruskan ke Guru BK</span>
                    </div>
                </div>

                <!-- Detecting Loader Status -->
                <div x-show="detecting" x-cloak class="mb-5 p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center gap-2.5 text-xs text-slate-600">
                    <div class="h-4 w-4 border-2 border-slate-300 border-t-slate-800 rounded-full animate-spin"></div>
                    <span>AI sedang menganalisis topik kalimat Anda...</span>
                </div>

                <!-- Contoh Topik Cepat -->
                <div class="mb-6">
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Contoh Topik Cepat:</p>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" @click="setTemplate('Saya merasa kesulitan fokus belajar dan sering menunda mengerjakan tugas sekolah, sehingga nilai semester ini menurun.')" class="text-[11px] bg-slate-50 hover:bg-slate-100 text-slate-700 px-3 py-1.5 rounded-lg border border-slate-200 transition text-left">
                            Masalah Belajar & Nilai
                        </button>
                        <button type="button" @click="setTemplate('Saya bingung memilih antara lanjut kuliah atau langsung bekerja setelah lulus SMK.')" class="text-[11px] bg-slate-50 hover:bg-slate-100 text-slate-700 px-3 py-1.5 rounded-lg border border-slate-200 transition text-left">
                            Karier & Dunia Kerja
                        </button>
                        <button type="button" @click="setTemplate('Saya merasa cemas tentang persiapan masuk perguruan tinggi dan beasiswa.')" class="text-[11px] bg-slate-50 hover:bg-slate-100 text-slate-700 px-3 py-1.5 rounded-lg border border-slate-200 transition text-left">
                            Perencanaan Masa Depan
                        </button>
                        <button type="button" @click="setTemplate('Saya merasa memiliki kendala komunikasi dengan teman sekelas dan butuh teman bercerita.')" class="text-[11px] bg-slate-50 hover:bg-slate-100 text-slate-700 px-3 py-1.5 rounded-lg border border-slate-200 transition text-left">
                            Sosial & Pertemanan
                        </button>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <a href="{{ route('student.dashboard') }}" class="text-xs font-medium text-slate-500 hover:text-slate-800 transition">
                        ← Kembali ke Dashboard
                    </a>

                    <button 
                        type="submit" 
                        :disabled="textCount < 10"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-xs text-white bg-slate-900 hover:bg-slate-800 disabled:opacity-40 disabled:cursor-not-allowed shadow-sm transition">
                        <span>Lanjutkan ke Pemilihan Guru BK</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
        </div>

    </div>

    <script>
        function aiConsultForm() {
            return {
                complaintText: @json(old('complaint', '')),
                textCount: 0,
                loading: false,
                detecting: false,
                debounceTimer: null,
                aiResult: {
                    detected: false,
                    category_name: '',
                    summary: '',
                    provider: ''
                },
                init() {
                    this.textCount = this.complaintText.length;
                    if (this.textCount >= 10) {
                        this.triggerLiveAnalysis();
                    }
                },
                handleInput() {
                    this.textCount = this.complaintText.length;
                    clearTimeout(this.debounceTimer);
                    if (this.textCount >= 12) {
                        this.debounceTimer = setTimeout(() => {
                            this.triggerLiveAnalysis();
                        }, 600);
                    } else {
                        this.aiResult.detected = false;
                    }
                },
                setTemplate(text) {
                    this.complaintText = text;
                    this.textCount = text.length;
                    this.triggerLiveAnalysis();
                },
                triggerLiveAnalysis() {
                    if (this.textCount < 10) return;
                    this.detecting = true;
                    
                    fetch('{{ route('student.ai-consult.live-preview') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ complaint: this.complaintText })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.detecting = false;
                        if (data.success) {
                            this.aiResult = {
                                detected: true,
                                category_name: data.category_name,
                                summary: data.summary,
                                provider: data.provider
                            };
                        }
                    })
                    .catch(() => {
                        this.detecting = false;
                    });
                },
                handleSubmit(e) {
                    if (this.textCount < 10) {
                        e.preventDefault();
                        return;
                    }
                    this.loading = true;
                }
            }
        }
    </script>
</x-app-layout>
