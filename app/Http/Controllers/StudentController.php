<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CounselingSession;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Dashboard Siswa
     */
    public function index(): View
    {
        $student = Auth::user();

        $recentSessions = CounselingSession::with(['counselor', 'category'])
            ->where('student_id', $student->id)
            ->latest()
            ->get();

        $activeSessionsCount = $recentSessions->whereIn('status', ['pending', 'accepted'])->count();
        $completedSessionsCount = $recentSessions->where('status', 'completed')->count();

        return view('student.dashboard', compact(
            'student',
            'recentSessions',
            'activeSessionsCount',
            'completedSessionsCount'
        ));
    }

    /**
     * Halaman Pemilihan Guru BK setelah analisis AI
     */
    public function selectCounselor(): View|RedirectResponse
    {
        if (! session()->has('draft_complaint')) {
            return redirect()->route('student.ai-consult.form')
                ->with('info', 'Silakan ceritakan permasalahanmu terlebih dahulu pada form AI Pra-Konseling.');
        }

        $categoryId = session('ai_category_id');
        $category = Category::findOrFail($categoryId);
        $aiSummary = session('ai_summary');
        $aiProvider = session('ai_provider', 'AI Engine');
        $originalComplaint = session('draft_complaint');

        // Ambil daftar guru BK, urutkan yang ready terlebih dahulu
        $counselors = User::where('role', 'guru_bk')
            ->orderByDesc('is_ready')
            ->orderBy('name')
            ->get();

        return view('student.select-counselor', compact(
            'category',
            'aiSummary',
            'aiProvider',
            'originalComplaint',
            'counselors'
        ));
    }

    /**
     * Submit permohonan sesi konseling ke Guru BK
     */
    public function createSession(Request $request): RedirectResponse
    {
        $request->validate([
            'counselor_id' => ['required', 'exists:users,id'],
        ], [
            'counselor_id.required' => 'Pilih Guru BK yang tersedia.',
            'counselor_id.exists' => 'Guru BK tidak valid.',
        ]);

        if (! session()->has('draft_complaint')) {
            return redirect()->route('student.dashboard')
                ->with('error', 'Sesi draf telah kedaluwarsa. Silakan mulai kembali.');
        }

        $counselor = User::where('id', $request->counselor_id)
            ->where('role', 'guru_bk')
            ->firstOrFail();

        if (! $counselor->is_ready) {
            return back()->with('error', "Mohon maaf, {$counselor->name} sedang tidak siap menerima sesi konseling.");
        }

        $session = CounselingSession::create([
            'student_id' => Auth::id(),
            'counselor_id' => $counselor->id,
            'ai_category_id' => session('ai_category_id'),
            'original_complaint' => session('draft_complaint'),
            'ai_summary' => session('ai_summary'),
            'status' => 'pending',
        ]);

        // Hapus data sesi draft
        session()->forget(['draft_complaint', 'ai_category_id', 'ai_category_name', 'ai_summary', 'ai_provider']);

        return redirect()->route('student.dashboard')
            ->with('success', "Permintaan bimbingan konseling berhasil dikirimkan kepada {$counselor->name}. Guru BK akan segera merespons!");
    }
}
