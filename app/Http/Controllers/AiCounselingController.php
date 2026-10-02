<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\AiTriageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AiCounselingController extends Controller
{
    public function __construct(
        protected AiTriageService $aiTriageService
    ) {}

    /**
     * Tampilkan form input keluhan siswa.
     */
    public function showForm(): View
    {
        $categories = Category::all();

        return view('student.ai-consult', compact('categories'));
    }

    /**
     * Menganalisis keluhan siswa dengan AI NLP Service.
     */
    public function analyze(Request $request): RedirectResponse
    {
        $request->validate([
            'complaint' => ['required', 'string', 'min:10', 'max:2500'],
        ], [
            'complaint.required' => 'Silakan ceritakan permasalahan atau keluhanmu terlebih dahulu.',
            'complaint.min' => 'Keluhan minimal 10 karakter agar AI dapat menganalisis konteksnya dengan tepat.',
            'complaint.max' => 'Keluhan maksimal 2500 karakter.',
        ]);

        $complaintText = $request->input('complaint');

        // Panggil AI NLP Triage Service
        $analysis = $this->aiTriageService->analyze($complaintText);

        $category = Category::where('name', $analysis['category_name'])->first();
        if (! $category) {
            $category = Category::firstOrCreate(
                ['name' => $analysis['category_name']],
                [
                    'slug' => Str::slug($analysis['category_name']),
                    'badge_color' => 'indigo',
                    'description' => 'Kategori hasil analisis AI.',
                ]
            );
        }

        // Simpan hasil klasifikasi ke session
        session([
            'draft_complaint' => $complaintText,
            'ai_category_id' => $category->id,
            'ai_category_name' => $category->name,
            'ai_summary' => $analysis['summary'],
            'ai_provider' => $analysis['provider'],
        ]);

        return redirect()->route('student.select-counselor')
            ->with('success', 'AI berhasil menganalisis dan mengklasifikasikan keluhanmu!');
    }

    /**
     * Endpoint API AJAX untuk preview deteksi kategori AI secara real-time.
     */
    public function livePreview(Request $request): JsonResponse
    {
        $complaint = (string) $request->input('complaint', '');

        if (strlen(trim($complaint)) < 8) {
            return response()->json([
                'success' => false,
                'message' => 'Teks terlalu pendek untuk dianalisis.',
            ]);
        }

        $analysis = $this->aiTriageService->analyze($complaint);

        return response()->json([
            'success' => true,
            'category_name' => $analysis['category_name'],
            'summary' => $analysis['summary'],
            'provider' => $analysis['provider'],
        ]);
    }
}
