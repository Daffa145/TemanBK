<x-app-layout>
    <div class="max-w-5xl mx-auto py-5 px-4 sm:px-6 lg:px-8 space-y-4" x-data="{ tab: 'profile' }">
        
        <!-- Page Header -->
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Pengaturan Akun</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola informasi pribadi, kontak, dan keamanan kata sandi Anda</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
            
            <!-- Left Sidebar Navigation -->
            <div class="bg-white rounded-xl border border-slate-200 p-2 shadow-2xs space-y-1">
                <button 
                    type="button" 
                    @click="tab = 'profile'" 
                    :class="tab === 'profile' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                    class="w-full text-left px-3.5 py-2 rounded-lg text-xs font-semibold transition">
                    Informasi Profil
                </button>

                <button 
                    type="button" 
                    @click="tab = 'password'" 
                    :class="tab === 'password' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'"
                    class="w-full text-left px-3.5 py-2 rounded-lg text-xs font-semibold transition">
                    Kata Sandi
                </button>

                <button 
                    type="button" 
                    @click="tab = 'delete'" 
                    :class="tab === 'delete' ? 'bg-rose-600 text-white' : 'text-rose-600 hover:bg-rose-50'"
                    class="w-full text-left px-3.5 py-2 rounded-lg text-xs font-semibold transition">
                    Hapus Akun
                </button>
            </div>

            <!-- Right Content Area -->
            <div class="md:col-span-3">
                <!-- 1. Tab Profil -->
                <div x-show="tab === 'profile'" x-cloak class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <!-- 2. Tab Password -->
                <div x-show="tab === 'password'" x-cloak class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs">
                    @include('profile.partials.update-password-form')
                </div>

                <!-- 3. Tab Hapus Akun -->
                <div x-show="tab === 'delete'" x-cloak class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
