<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6" x-data="categoryManagement()">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block mb-1">Manajemen Kategori</span>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Kategori & Bidang Konseling</h1>
                <p class="text-xs text-slate-500 mt-0.5">Kelola kategori yang digunakan untuk klasifikasi masalah dan penugasan guru.</p>
            </div>

            <button @click="openCreateModal()" class="px-4 py-2.5 rounded-xl font-semibold text-xs text-white bg-slate-900 hover:bg-slate-800 shadow-sm transition">
                + Tambah Kategori
            </button>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse($categories as $cat)
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs flex flex-col justify-between space-y-3">
                    <div>
                        <div class="flex items-start justify-between gap-2 mb-1.5">
                            <span class="font-bold text-slate-900 text-sm">{{ $cat->name }}</span>
                            <span class="text-[10px] font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md">
                                {{ $cat->sessions_count }} Sesi
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            {{ $cat->description ?? 'Tidak ada deskripsi.' }}
                        </p>
                    </div>

                    <div class="flex items-center justify-end gap-1.5 pt-3 border-t border-slate-100">
                        <button 
                            type="button" 
                            @click="openEditModal({{ json_encode($cat) }})" 
                            class="px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                            Edit
                        </button>
                        <form action="{{ route('admin.categories.delete', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ addslashes($cat->name) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2.5 py-1 rounded-lg text-xs font-medium bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 transition">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-4 p-8 text-center bg-white rounded-2xl border border-slate-200 text-xs text-slate-400">
                    Belum ada kategori konseling.
                </div>
            @endforelse
        </div>

        <!-- Modal Tambah / Edit Kategori -->
        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div @click="modalOpen = false" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>

                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-slate-900" x-text="isEdit ? 'Edit Kategori' : 'Tambah Kategori Baru'"></h3>
                        <button @click="modalOpen = false" class="text-slate-400 hover:text-slate-600 font-bold text-base">✕</button>
                    </div>

                    <form :action="formAction" method="POST" class="space-y-3.5">
                        @csrf
                        <template x-if="isEdit">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Kategori *</label>
                            <input type="text" name="name" x-model="formData.name" placeholder="Contoh: Kedisiplinan & Karakter" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs text-slate-900 focus:ring-2 focus:ring-slate-900" required>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Warna Label *</label>
                            <select name="badge_color" x-model="formData.badge_color" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-slate-900 font-medium">
                                <option value="indigo">Indigo</option>
                                <option value="emerald">Emerald</option>
                                <option value="purple">Purple</option>
                                <option value="rose">Rose</option>
                                <option value="amber">Amber</option>
                                <option value="blue">Blue</option>
                                <option value="teal">Teal</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Ruang Lingkup</label>
                            <textarea name="description" x-model="formData.description" rows="3" placeholder="Jelaskan jenis permasalahan yang masuk kategori ini..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs text-slate-900 focus:ring-2 focus:ring-slate-900"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                            <button @click="modalOpen = false" type="button" class="px-4 py-2 rounded-xl text-xs font-medium text-slate-600 hover:bg-slate-100 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 transition">
                                <span x-text="isEdit ? 'Simpan Perubahan' : 'Simpan Kategori'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        function categoryManagement() {
            return {
                modalOpen: false,
                isEdit: false,
                formAction: '{{ route('admin.categories.store') }}',
                formData: {
                    name: '',
                    badge_color: 'indigo',
                    description: '',
                },
                openCreateModal() {
                    this.isEdit = false;
                    this.formAction = '{{ route('admin.categories.store') }}';
                    this.formData = {
                        name: '',
                        badge_color: 'indigo',
                        description: '',
                    };
                    this.modalOpen = true;
                },
                openEditModal(cat) {
                    this.isEdit = true;
                    this.formAction = '{{ url('admin/categories') }}/' + cat.id;
                    this.formData = {
                        name: cat.name || '',
                        badge_color: cat.badge_color || 'indigo',
                        description: cat.description || '',
                    };
                    this.modalOpen = true;
                }
            }
        }
    </script>
</x-app-layout>
