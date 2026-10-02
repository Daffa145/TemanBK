<?php

namespace App\Http\Controllers;

use App\Models\CounselingSession;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CounselorController extends Controller
{
    /**
     * Dashboard Guru BK
     */
    public function index(): View
    {
        $counselor = Auth::user();

        // 1. Permintaan Masuk (status pending)
        $pendingSessions = CounselingSession::with(['student', 'category'])
            ->where('counselor_id', $counselor->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        // 2. Sesi Aktif (status accepted)
        $activeSessions = CounselingSession::with(['student', 'category'])
            ->where('counselor_id', $counselor->id)
            ->where('status', 'accepted')
            ->latest()
            ->get();

        // 3. Riwayat Selesai (status completed)
        $completedSessions = CounselingSession::with(['student', 'category'])
            ->where('counselor_id', $counselor->id)
            ->where('status', 'completed')
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'pending' => $pendingSessions->count(),
            'active' => $activeSessions->count(),
            'completed' => CounselingSession::where('counselor_id', $counselor->id)->where('status', 'completed')->count(),
        ];

        return view('counselor.dashboard', compact(
            'counselor',
            'pendingSessions',
            'activeSessions',
            'completedSessions',
            'stats'
        ));
    }

    /**
     * Toggle status ketersediaan Guru BK (is_ready)
     */
    public function toggleReady(): RedirectResponse
    {
        /** @var User $counselor */
        $counselor = Auth::user();
        $counselor->is_ready = ! $counselor->is_ready;
        $counselor->save();

        $statusText = $counselor->is_ready
            ? 'Status diperbarui: SIAP KONSELING (🟢 Ready)'
            : 'Status diperbarui: SEDANG ISTIRAHAT / SIBUK (🔴 Not Ready)';

        return back()->with('success', $statusText);
    }

    /**
     * Terima permintaan konseling dari siswa
     */
    public function acceptRequest(int $id): RedirectResponse
    {
        $session = CounselingSession::where('id', $id)
            ->where('counselor_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $session->update([
            'status' => 'accepted',
        ]);

        return back()->with('success', "Permintaan konseling dari {$session->student->name} telah DITERIMA.");
    }

    /**
     * Selesaikan sesi konseling dan berikan catatan/tindak lanjut
     */
    public function completeSession(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'counselor_notes' => ['required', 'string', 'min:5', 'max:3000'],
        ], [
            'counselor_notes.required' => 'Mohon isi catatan hasil atau rekomendasi konseling.',
            'counselor_notes.min' => 'Catatan konseling minimal 5 karakter.',
        ]);

        $session = CounselingSession::where('id', $id)
            ->where('counselor_id', Auth::id())
            ->where('status', 'accepted')
            ->firstOrFail();

        $session->update([
            'status' => 'completed',
            'counselor_notes' => $request->input('counselor_notes'),
        ]);

        return back()->with('success', "Sesi konseling bersama {$session->student->name} telah berhasil diselesaikan.");
    }

    /**
     * Tolak permintaan konseling jika berhalangan
     */
    public function rejectRequest(Request $request, int $id): RedirectResponse
    {
        $session = CounselingSession::where('id', $id)
            ->where('counselor_id', Auth::id())
            ->where('status', 'pending')
            ->firstOrFail();

        $session->update([
            'status' => 'rejected',
            'counselor_notes' => $request->input('reason', 'Guru BK sedang berhalangan.'),
        ]);

        return back()->with('info', "Permintaan konseling dari {$session->student->name} telah ditolak.");
    }
}
