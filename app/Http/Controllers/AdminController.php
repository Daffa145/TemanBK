<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CounselingSession;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Dashboard Utama Admin
     */
    public function index(): View
    {
        $stats = [
            'total_students' => User::where('role', 'siswa')->count(),
            'total_counselors' => User::where('role', 'guru_bk')->count(),
            'ready_counselors' => User::where('role', 'guru_bk')->where('is_ready', true)->count(),
            'total_sessions' => CounselingSession::count(),
            'pending_sessions' => CounselingSession::where('status', 'pending')->count(),
            'active_sessions' => CounselingSession::where('status', 'accepted')->count(),
            'completed_sessions' => CounselingSession::where('status', 'completed')->count(),
            'total_categories' => Category::count(),
        ];

        $recentSessions = CounselingSession::with(['student', 'counselor', 'category'])
            ->latest()
            ->take(15)
            ->get();

        $counselors = User::where('role', 'guru_bk')->get();
        $categories = Category::withCount('sessions')->get();

        $periods = [
            'hari' => [
                'label' => 'Hari Ini',
                'start' => now()->startOfDay(),
            ],
            'minggu' => [
                'label' => 'Minggu Ini',
                'start' => now()->startOfWeek(),
            ],
            'bulan' => [
                'label' => 'Bulan Ini',
                'start' => now()->startOfMonth(),
            ],
            'tahun' => [
                'label' => 'Tahun Ini',
                'start' => now()->startOfYear(),
            ],
        ];

        $chartData = [];
        foreach ($periods as $key => $period) {
            $sessionsInPeriod = CounselingSession::where('created_at', '>=', $period['start'])->get();
            $totalInPeriod = $sessionsInPeriod->count();

            $labels = [];
            $counts = [];
            $breakdown = [];

            foreach ($categories as $cat) {
                $count = $sessionsInPeriod->where('ai_category_id', $cat->id)->count();
                $percentage = $totalInPeriod > 0 ? round(($count / $totalInPeriod) * 100) : 0;

                $labels[] = $cat->name;
                $counts[] = $count;
                $breakdown[] = [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'count' => $count,
                    'percentage' => $percentage,
                ];
            }

            $chartData[$key] = [
                'label' => $period['label'],
                'total' => $totalInPeriod,
                'labels' => $labels,
                'counts' => $counts,
                'breakdown' => $breakdown,
            ];
        }

        return view('admin.dashboard', compact('stats', 'recentSessions', 'counselors', 'categories', 'chartData'));
    }

    // ==========================================
    // MANAJEMEN PENGGUNA (USERS)
    // ==========================================

    public function users(Request $request): View
    {
        $roleFilter = $request->query('role');
        $search = $request->query('search');

        $query = User::query();

        if ($roleFilter && in_array($roleFilter, ['siswa', 'guru_bk', 'admin'], true)) {
            $query->where('role', $roleFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::all();

        return view('admin.users.index', compact('users', 'roleFilter', 'search', 'categories'));
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:25'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            'role' => ['required', Rule::in(['siswa', 'guru_bk', 'admin'])],
            'password' => ['required', 'min:6', 'max:15'],
            'nis' => ['nullable', 'string', 'max:7', 'unique:users,nis'],
            'kelas' => ['nullable', 'string', 'max:10'],
            'jurusan' => ['nullable', 'string', 'max:7'],
            'nip' => ['nullable', 'string', 'max:50', 'unique:users,nip'],
            'spesialisasi_kategori' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:14'],
            'is_ready' => ['nullable', 'boolean'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'nip' => $request->nip,
            'spesialisasi_kategori' => $request->spesialisasi_kategori,
            'no_hp' => $request->no_hp,
            'is_ready' => (bool) $request->is_ready,
        ]);

        return back()->with('success', "Pengguna {$request->name} berhasil ditambahkan!");
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:25'],
            'email' => ['required', 'string', 'email', 'max:50', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['siswa', 'guru_bk', 'admin'])],
            'password' => ['nullable', 'min:6', 'max:15'],
            'nis' => ['nullable', 'string', 'max:7', Rule::unique('users')->ignore($user->id)],
            'kelas' => ['nullable', 'string', 'max:10'],
            'jurusan' => ['nullable', 'string', 'max:7'],
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'spesialisasi_kategori' => ['nullable', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:14'],
            'is_ready' => ['nullable', 'boolean'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'nip' => $request->nip,
            'spesialisasi_kategori' => $request->spesialisasi_kategori,
            'no_hp' => $request->no_hp,
            'is_ready' => (bool) $request->is_ready,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', "Data pengguna {$user->name} berhasil diperbarui!");
    }

    public function deleteUser(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "Pengguna {$name} berhasil dihapus.");
    }

    public function toggleCounselorReady(User $user): RedirectResponse
    {
        if (! $user->isGuruBk()) {
            return back()->with('error', 'Pengguna bukan merupakan Guru BK.');
        }

        $user->is_ready = ! $user->is_ready;
        $user->save();

        $status = $user->is_ready ? 'READY 🟢' : 'NOT READY 🔴';

        return back()->with('success', "Status Guru BK {$user->name} diubah menjadi {$status}.");
    }

    // ==========================================
    // MANAJEMEN KATEGORI (CATEGORIES)
    // ==========================================

    public function categories(): View
    {
        $categories = Category::withCount('sessions')->latest()->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:500'],
            'badge_color' => ['required', 'string', Rule::in(['indigo', 'emerald', 'purple', 'rose', 'amber', 'blue', 'teal', 'cyan'])],
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'badge_color' => $request->badge_color,
        ]);

        return back()->with('success', "Kategori {$request->name} berhasil ditambahkan!");
    }

    public function updateCategory(Request $request, Category $category): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('categories')->ignore($category->id)],
            'description' => ['nullable', 'string', 'max:500'],
            'badge_color' => ['required', 'string', Rule::in(['indigo', 'emerald', 'purple', 'rose', 'amber', 'blue', 'teal', 'cyan'])],
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'badge_color' => $request->badge_color,
        ]);

        return back()->with('success', "Kategori {$category->name} berhasil diperbarui!");
    }

    public function deleteCategory(Category $category): RedirectResponse
    {
        $name = $category->name;
        $category->delete();

        return back()->with('success', "Kategori {$name} berhasil dihapus.");
    }

    // ==========================================
    // MONITORING & MANAJEMEN SESI KONSELING
    // ==========================================

    public function sessions(Request $request): View
    {
        $statusFilter = $request->query('status');
        $categoryFilter = $request->query('category_id');
        $counselorFilter = $request->query('counselor_id');

        $query = CounselingSession::with(['student', 'counselor', 'category']);

        if ($statusFilter && in_array($statusFilter, ['pending', 'accepted', 'completed', 'rejected'], true)) {
            $query->where('status', $statusFilter);
        }

        if ($categoryFilter) {
            $query->where('ai_category_id', $categoryFilter);
        }

        if ($counselorFilter) {
            $query->where('counselor_id', $counselorFilter);
        }

        $sessions = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::all();
        $counselors = User::where('role', 'guru_bk')->get();

        return view('admin.sessions.index', compact('sessions', 'categories', 'counselors', 'statusFilter', 'categoryFilter', 'counselorFilter'));
    }

    public function reassignSession(Request $request, CounselingSession $session): RedirectResponse
    {
        $request->validate([
            'counselor_id' => ['required', 'exists:users,id'],
        ]);

        $counselor = User::where('id', $request->counselor_id)->where('role', 'guru_bk')->firstOrFail();

        $session->update([
            'counselor_id' => $counselor->id,
        ]);

        return back()->with('success', "Sesi konseling siswa {$session->student->name} dialihkan kepada {$counselor->name}.");
    }

    public function updateSessionStatus(Request $request, CounselingSession $session): RedirectResponse
    {
        $request->validate([
            'status' => ['required', Rule::in(['pending', 'accepted', 'completed', 'rejected'])],
            'counselor_notes' => ['nullable', 'string'],
        ]);

        $session->update([
            'status' => $request->status,
            'counselor_notes' => $request->counselor_notes,
        ]);

        return back()->with('success', "Status sesi #{$session->id} berhasil diperbarui.");
    }

    public function deleteSession(CounselingSession $session): RedirectResponse
    {
        $session->delete();

        return back()->with('success', 'Sesi konseling telah dihapus.');
    }

    // ==========================================
    // PENGATURAN SISTEM & AI
    // ==========================================

    public function settings(): View
    {
        $aiProvider = config('services.ai_provider', 'openai');
        $openaiConfigured = ! empty(config('services.openai.api_key'));
        $geminiConfigured = ! empty(config('services.gemini.api_key'));

        return view('admin.settings', compact('aiProvider', 'openaiConfigured', 'geminiConfigured'));
    }
}
