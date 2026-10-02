<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Header Section -->
        <div
            class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-2xs flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block mb-1">Panel
                    Administrator</span>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Ringkasan Sistem & Monitoring
                </h1>
                <p class="text-slate-600 text-xs sm:text-sm mt-1 max-w-xl leading-relaxed">
                    Pantau metrik bimbingan konseling, ketersediaan Guru BK, dan data pengguna sekolah secara real-time.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.users.index') }}"
                    class="px-3.5 py-2 rounded-xl font-semibold text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                    Kelola Pengguna
                </a>
                <a href="{{ route('admin.categories.index') }}"
                    class="px-3.5 py-2 rounded-xl font-semibold text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                    Kategori Masalah
                </a>
                <a href="{{ route('admin.sessions.index') }}"
                    class="px-3.5 py-2 rounded-xl font-semibold text-xs bg-slate-900 hover:bg-slate-800 text-white transition">
                    Pantau Seluruh Sesi
                </a>
            </div>
        </div>

        <!-- Metric Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Total Siswa</span>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['total_students'] }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Siswa terdaftar</p>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Guru BK
                    Aktif</span>
                <p class="text-2xl font-bold text-slate-900 mt-1">
                    {{ $stats['ready_counselors'] }} <span class="text-xs font-normal text-slate-500">/
                        {{ $stats['total_counselors'] }} Guru</span>
                </p>
                <p class="text-[11px] text-emerald-700 font-medium mt-0.5">Status Ready</p>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Permintaan
                    Pending</span>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['pending_sessions'] }}</p>
                <p class="text-[11px] text-amber-700 font-medium mt-0.5">Menunggu respons guru</p>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-2xs">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Total
                    Diselesaikan</span>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $stats['completed_sessions'] }}</p>
                <p class="text-[11px] text-slate-400 mt-0.5">Dari {{ $stats['total_sessions'] }} total konsultasi</p>
            </div>
        </div>

        <!-- 2 Column: Category Breakdown & Counselor Availability -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Category Distribution Chart Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-2xs space-y-4"
                x-data="categoryDistributionChart()">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div>
                        <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Distribusi Bidang Masalah
                        </h2>
                        <p class="text-[11px] text-slate-500 mt-0.5">Grafik permohonan konseling</p>
                    </div>
                    <a href="{{ route('admin.categories.index') }}"
                        class="text-xs text-slate-500 hover:text-slate-800 font-medium">
                        Kelola
                    </a>
                </div>

                <!-- Periode Selector Pills (Hari, Minggu, Bulan, Tahun) -->
                <div class="flex items-center justify-between bg-slate-100 p-1 rounded-xl gap-1">
                    <button type="button" @click="setPeriod('hari')"
                        :class="period === 'hari' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                        class="flex-1 py-1.5 px-2 text-[11px] rounded-lg transition text-center cursor-pointer">
                        Hari
                    </button>
                    <button type="button" @click="setPeriod('minggu')"
                        :class="period === 'minggu' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                        class="flex-1 py-1.5 px-2 text-[11px] rounded-lg transition text-center cursor-pointer">
                        Minggu
                    </button>
                    <button type="button" @click="setPeriod('bulan')"
                        :class="period === 'bulan' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                        class="flex-1 py-1.5 px-2 text-[11px] rounded-lg transition text-center cursor-pointer">
                        Bulan
                    </button>
                    <button type="button" @click="setPeriod('tahun')"
                        :class="period === 'tahun' ? 'bg-white text-slate-900 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900 font-medium'"
                        class="flex-1 py-1.5 px-2 text-[11px] rounded-lg transition text-center cursor-pointer">
                        Tahun
                    </button>
                </div>

                <!-- Chart Canvas Container -->
                <div class="relative h-44 w-full">
                    <canvas id="categoryDistributionCanvas"></canvas>
                </div>

                <!-- Breakdown List with Percentages and Counts -->
                <div class="space-y-2.5 pt-2 border-t border-slate-100">
                    <template x-for="item in currentBreakdown" :key="item.id">
                        <div>
                            <div class="flex justify-between text-xs text-slate-700 mb-1">
                                <span class="font-medium" x-text="item.name"></span>
                                <span class="text-slate-500">
                                    <span x-text="item.count"></span> sesi (<span x-text="item.percentage"></span>%)
                                </span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                <div class="bg-slate-800 h-1.5 rounded-full transition-all duration-300"
                                    :style="'width: ' + Math.max(item.percentage, item.count > 0 ? 4 : 0) + '%'"></div>
                            </div>
                        </div>
                    </template>

                    <div class="text-[11px] text-slate-400 text-right pt-1">
                        Total: <strong class="text-slate-700" x-text="currentTotal"></strong> sesi (<span
                            x-text="currentLabel"></span>)
                    </div>
                </div>
            </div>

            <!-- Guru BK Availability Monitor -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200 shadow-2xs">
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                    <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Status Ketersediaan Guru BK
                    </h2>
                    <a href="{{ route('admin.users.index', ['role' => 'guru_bk']) }}"
                        class="text-xs text-slate-500 hover:text-slate-800">
                        Semua Guru
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @forelse($counselors as $counselor)
                        <div
                            class="p-3.5 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between gap-3">
                            <div class="space-y-0.5">
                                <h3 class="text-xs font-bold text-slate-900">{{ $counselor->name }}</h3>
                                <p class="text-[11px] text-slate-500">Spesialisasi:
                                    {{ $counselor->spesialisasi_kategori ?? 'Umum' }}</p>
                            </div>

                            <form action="{{ route('admin.users.toggle-ready', $counselor->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="px-2.5 py-1 rounded-lg text-xs font-semibold border transition {{ $counselor->is_ready ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                    {{ $counselor->is_ready ? 'Ready' : 'Off' }}
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 col-span-2">Belum ada data Guru BK.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Sessions Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Sesi Bimbingan Terbaru</h2>
                    <p class="text-xs text-slate-500">Aktivitas permohonan bimbingan konseling yang masuk</p>
                </div>
                <a href="{{ route('admin.sessions.index') }}"
                    class="text-xs font-semibold text-slate-600 hover:text-slate-900">
                    Buka Seluruh Sesi →
                </a>
            </div>

            <div class="overflow-x-auto max-h-[285px] overflow-y-auto">
                <table class="w-full text-left text-xs">
                    <thead
                        class="bg-slate-50 border-b border-slate-100 text-slate-500 font-semibold uppercase tracking-wider sticky top-0 z-10">
                        <tr>
                            <th class="p-3.5">Siswa</th>
                            <th class="p-3.5">Kategori</th>
                            <th class="p-3.5">Guru BK</th>
                            <th class="p-3.5">Ringkasan</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentSessions as $session)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-3.5">
                                    <div class="font-semibold text-slate-900">{{ $session->student->name }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $session->student->kelas ?? '-' }}</div>
                                </td>
                                <td class="p-3.5">
                                    <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700">
                                        {{ $session->category->name ?? 'Umum' }}
                                    </span>
                                </td>
                                <td class="p-3.5 font-medium text-slate-800">
                                    {{ $session->counselor->name ?? 'Belum Ditugaskan' }}
                                </td>
                                <td class="p-3.5 text-slate-600 max-w-xs truncate">
                                    {{ $session->ai_summary ?? '-' }}
                                </td>
                                <td class="p-3.5">
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
                                <td class="p-3.5 text-slate-400 whitespace-nowrap">
                                    {{ $session->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-slate-400">Belum ada aktivitas sesi bimbingan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        function categoryDistributionChart() {
            return {
                period: 'bulan',
                chartData: @js($chartData),
                chartInstance: null,

                get currentPeriodData() {
                    return this.chartData[this.period] || { labels: [], counts: [], breakdown: [], total: 0, label: '' };
                },

                get currentBreakdown() {
                    return this.currentPeriodData.breakdown || [];
                },

                get currentTotal() {
                    return this.currentPeriodData.total || 0;
                },

                get currentLabel() {
                    return this.currentPeriodData.label || '';
                },

                init() {
                    this.$nextTick(() => {
                        this.renderChart();
                    });
                },

                setPeriod(p) {
                    this.period = p;
                    this.updateChart();
                },

                renderChart() {
                    const canvas = document.getElementById('categoryDistributionCanvas');
                    if (!canvas || typeof Chart === 'undefined') return;

                    const periodData = this.currentPeriodData;
                    const maxVal = Math.max(...(periodData.counts.length ? periodData.counts : [0]), 2);
                    const palette = [
                        '#1e293b', // slate-800
                        '#4f46e5', // indigo-600
                        '#059669', // emerald-600
                        '#d97706', // amber-600
                        '#dc2626', // rose-600
                        '#0891b2', // cyan-600
                    ];

                    this.chartInstance = new Chart(canvas, {
                        type: 'bar',
                        data: {
                            labels: periodData.labels,
                            datasets: [{
                                label: 'Jumlah Sesi',
                                data: periodData.counts,
                                backgroundColor: palette.slice(0, periodData.labels.length),
                                borderRadius: 6,
                                barPercentage: 0.6,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: '#0f172a',
                                    titleFont: { size: 11, family: 'Plus Jakarta Sans' },
                                    bodyFont: { size: 11, family: 'Plus Jakarta Sans' },
                                    padding: 8,
                                    cornerRadius: 8,
                                    callbacks: {
                                        label: function (context) {
                                            return context.parsed.y + ' sesi konseling';
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: {
                                        font: { size: 10, family: 'Plus Jakarta Sans' },
                                        color: '#64748b'
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    suggestedMax: maxVal + 1,
                                    ticks: {
                                        stepSize: 1,
                                        precision: 0,
                                        font: { size: 10, family: 'Plus Jakarta Sans' },
                                        color: '#64748b'
                                    },
                                    grid: {
                                        color: '#f1f5f9'
                                    }
                                }
                            }
                        }
                    });
                },

                updateChart() {
                    if (!this.chartInstance) return;
                    const periodData = this.currentPeriodData;
                    const maxVal = Math.max(...(periodData.counts.length ? periodData.counts : [0]), 2);
                    this.chartInstance.data.labels = periodData.labels;
                    this.chartInstance.data.datasets[0].data = periodData.counts;
                    this.chartInstance.options.scales.y.suggestedMax = maxVal + 1;
                    this.chartInstance.update();
                }
            };
        }
    </script>
</x-app-layout>