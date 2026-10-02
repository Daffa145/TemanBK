<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AiTriageService
{
    /**
     * Analyze and classify student complaint using AI (OpenAI / Gemini / Fallback).
     *
     * @return array{category_name: string, summary: string, provider: string}
     */
    public function analyze(string $complaint): array
    {
        $categories = Category::pluck('name')->toArray();
        if (empty($categories)) {
            $categories = ['Belajar', 'Karier', 'Masa Depan', 'Permasalahan Sosial'];
        }

        $provider = config('services.ai_provider', 'openai');
        $openaiKey = config('services.openai.api_key');
        $geminiKey = config('services.gemini.api_key');

        // 1. Coba OpenAI jika API key tersedia & provider openai
        if ($openaiKey && ($provider === 'openai' || empty($geminiKey))) {
            $result = $this->callOpenAI($complaint, $categories, $openaiKey);
            if ($result) {
                return $result;
            }
        }

        // 2. Coba Gemini jika API key tersedia
        if ($geminiKey) {
            $result = $this->callGemini($complaint, $categories, $geminiKey);
            if ($result) {
                return $result;
            }
        }

        // 3. Fallback Heuristic NLP Classifier (Cerdas & Cepat tanpa API Key)
        return $this->fallbackAnalysis($complaint, $categories);
    }

    /**
     * OpenAI Chat Completion API
     */
    protected function callOpenAI(string $complaint, array $categories, string $apiKey): ?array
    {
        $categoryListStr = implode(', ', $categories);
        $systemPrompt = <<<PROMPT
Anda adalah asisten AI Pra-Konseling Sekolah BK (Bimbingan Konseling).
Tugas Anda:
1. Analisis keluhan siswa berikut dengan cermat dan penuh empati.
2. Klasifikasikan keluhan HANYA ke salah satu kategori resmi ini: [{$categoryListStr}].
3. Buat ringkasan padat 1-2 kalimat (summary) dari keluhan siswa tersebut agar Guru BK dapat langsung memahami akar permasalahannya.

Wajib kembalikan response HANYA dalam format JSON valid berikut tanpa markdown wrapper lain:
{
    "category": "Kategori Terpilih",
    "summary": "Ringkasan padat 1-2 kalimat dari inti permasalahan siswa."
}
PROMPT;

        try {
            $model = config('services.openai.model', 'gpt-4o-mini');
            $response = Http::withToken($apiKey)
                ->timeout(15)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => "Keluhan Siswa: \"{$complaint}\""],
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.2,
                ]);

            if ($response->successful()) {
                $content = json_decode($response->json('choices.0.message.content'), true);
                if (isset($content['category'], $content['summary'])) {
                    return [
                        'category_name' => $this->matchClosestCategory($content['category'], $categories),
                        'summary' => $content['summary'],
                        'provider' => 'OpenAI ('.$model.')',
                    ];
                }
            } else {
                Log::warning('OpenAI Triage Failed: '.$response->body());
            }
        } catch (\Throwable $e) {
            Log::error('OpenAI Exception: '.$e->getMessage());
        }

        return null;
    }

    /**
     * Google Gemini API (generateContent)
     */
    protected function callGemini(string $complaint, array $categories, string $apiKey): ?array
    {
        $categoryListStr = implode(', ', $categories);
        $prompt = <<<PROMPT
Anda adalah asisten AI Pra-Konseling Sekolah BK (Bimbingan Konseling).
Tugas Anda:
1. Analisis keluhan siswa berikut dengan cermat dan berikan klasifikasi yang tepat: "{$complaint}"
2. Klasifikasikan keluhan HANYA ke salah satu kategori resmi berikut: [{$categoryListStr}].
3. Buat ringkasan padat 1-2 kalimat (summary) dari inti permasalahan siswa untuk Guru BK.

Wajib berikan respon HANYA dalam format JSON valid tanpa format teks lain:
{
  "category": "Nama Kategori Terpilih",
  "summary": "Ringkasan padat 1-2 kalimat dari keluhan siswa."
}
PROMPT;

        $preferredModel = config('services.gemini.model', 'gemini-2.5-flash');
        $modelsToTry = array_unique([$preferredModel, 'gemini-2.5-flash', 'gemini-2.0-flash', 'gemini-1.5-flash', 'gemini-pro']);

        foreach ($modelsToTry as $model) {
            try {
                $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

                $response = Http::timeout(12)->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'responseMimeType' => 'application/json',
                        'temperature' => 0.2,
                    ],
                ]);

                if ($response->successful()) {
                    $rawText = $response->json('candidates.0.content.parts.0.text');
                    // Bersihkan jika ada code fence ```json
                    $cleanJson = preg_replace('/^```(?:json)?\s+|\s+```$/i', '', trim((string) $rawText));
                    $content = json_decode($cleanJson, true);

                    if (isset($content['category'], $content['summary'])) {
                        return [
                            'category_name' => $this->matchClosestCategory($content['category'], $categories),
                            'summary' => $content['summary'],
                            'provider' => 'Google Gemini ('.$model.')',
                        ];
                    }
                } else {
                    Log::warning("Gemini [{$model}] Triage Failed: ".$response->body());
                }
            } catch (\Throwable $e) {
                Log::error("Gemini [{$model}] Exception: ".$e->getMessage());
            }
        }

        return null;
    }

    /**
     * Smart Keyword & Semantic Fallback Classifier (Zero-config / Offline safe).
     */
    protected function fallbackAnalysis(string $complaint, array $categories): array
    {
        $lower = Str::lower($complaint);

        $scores = [
            'Belajar' => 0,
            'Karier' => 0,
            'Masa Depan' => 0,
            'Permasalahan Sosial' => 0,
        ];

        // Kamus Kata Kunci Komprehensif Berbagai Aspek Bimbingan Siswa
        $keywords = [
            'Belajar' => [
                'nilai', 'pelajaran', 'pr', 'tugas', 'ulangan', 'ujian', 'guru mapel', 'matematika',
                'fisika', 'kimia', 'biologi', 'bahasa', 'susah paham', 'malas belajar', 'fokus',
                'konsentrasi', 'ranking', 'peringkat', 'remedial', 'remed', 'akademik', 'akademis',
                'hafalan', 'sulit belajar', 'kurang paham', 'nilai jelek', 'nilai turun', 'semester',
                'rapor', 'bimbel', 'les', 'materi', 'teori', 'praktek', 'sekolah', 'bolos',
            ],
            'Karier' => [
                'kerja', 'bekerja', 'pekerjaan', 'jabatan', 'jabat', 'posisi', 'profesi', 'kantor',
                'pkl', 'magang', 'jurusan', 'kejuruan', 'rpl', 'tkj', 'akuntansi', 'otomotif',
                'lowongan', 'portofolio', 'keahlian', 'skill', 'gaji', 'perusahaan', 'cv', 'industri',
                'pelatihan', 'bisnis', 'wirausaha', 'usaha', 'organisasi', 'osis', 'kepemimpinan',
                'ketua', 'anggota', 'atasan', 'bawahan', 'karir', 'career', 'prakerin', 'dunia kerja',
                'interview', 'wawancara kerja', 'lamaran',
            ],
            'Masa Depan' => [
                'kuliah', 'universitas', 'kampus', 'snbt', 'snbp', 'sbmptn', 'utbk', 'beasiswa',
                'gap year', 'cita-cita', 'ptn', 'pts', 'kedinasan', 'tujuan hidup', 'bingung masa depan',
                'masa depan', 'prospek', 'rencana', 'akpol', 'akmil', 'ikatan dinas', 'sarjana',
                'diploma', 's1', 'd3', 'd4', 'fakultas', 'jurusan kuliah', 'prodi', 'perguruan tinggi',
                'jenjang', 'impian', 'target hidup',
            ],
            'Permasalahan Sosial' => [
                'teman', 'sekelas', 'sahabat', 'kawan', 'pertemanan', 'sosial', 'lingkungan',
                'komunikasi', 'canggung', 'pemalu', 'introvert', 'isolasi', 'dijauhi', 'disindir',
                'diejek', 'bully', 'dibully', 'perundungan', 'musuh', 'konflik', 'geng', 'pacar',
                'putus', 'cinta', 'keluarga', 'orang tua', 'ayah', 'ibu', 'broken home', 'saudara',
                'stres', 'stress', 'cemas', 'anxiety', 'overthinking', 'kesepian', 'emosi', 'marah',
                'sedih', 'mental', 'insecure', 'percaya diri', 'minder', 'tertekan', 'curhat',
            ],
        ];

        foreach ($keywords as $category => $words) {
            foreach ($words as $word) {
                if (Str::contains($lower, $word)) {
                    $scores[$category] += 2;
                }
            }
        }

        arsort($scores);
        $bestCategory = array_key_first($scores);

        // Jika tidak ada kata kunci yang cocok sama sekali
        if ($scores[$bestCategory] === 0) {
            // Cek indikator sosial/perasaan umum
            if (Str::contains($lower, ['rasa', 'merasa', 'takut', 'bingung', 'sedih', 'gimana', 'kenapa'])) {
                $bestCategory = 'Permasalahan Sosial';
            } else {
                $bestCategory = 'Belajar';
            }
        }

        // Cari nama kategori yang sesuai dengan kategori database
        $matchedCategory = $this->matchClosestCategory($bestCategory, $categories);

        // Buat ringkasan cerdas yang mencerminkan inti cerita
        $snippet = Str::limit(trim($complaint), 140, '...');
        $summary = "Siswa menyampaikan permasalahan seputar {$matchedCategory}: \"{$snippet}\"";

        return [
            'category_name' => $matchedCategory,
            'summary' => $summary,
            'provider' => 'Smart NLP Engine (TemanBK)',
        ];
    }

    /**
     * Helper to match nearest category string
     */
    protected function matchClosestCategory(string $detected, array $available): string
    {
        foreach ($available as $category) {
            if (stripos($detected, $category) !== false || stripos($category, $detected) !== false) {
                return $category;
            }
        }

        return $available[0] ?? 'Belajar';
    }
}
