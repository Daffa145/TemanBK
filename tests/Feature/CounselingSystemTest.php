<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CounselingSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CounselingSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Category::create([
            'name' => 'Belajar',
            'slug' => 'belajar',
            'description' => 'Masalah akademik',
            'badge_color' => 'indigo',
        ]);
        Category::create([
            'name' => 'Karier',
            'slug' => 'karier',
            'description' => 'Masalah karier',
            'badge_color' => 'emerald',
        ]);
    }

    public function test_student_can_submit_ai_counseling_complaint(): void
    {
        $student = User::factory()->create([
            'role' => 'siswa',
            'nis' => '12345',
            'kelas' => 'XII RPL 1',
        ]);

        $response = $this->actingAs($student)->post(route('student.ai-consult.analyze'), [
            'complaint' => 'Saya merasa sangat kesulitan belajar matematika dan nilai tugas saya selalu remedial.',
        ]);

        $response->assertRedirect(route('student.select-counselor'));
        $response->assertSessionHas('draft_complaint');
        $response->assertSessionHas('ai_category_name', 'Belajar');
    }

    public function test_student_can_select_ready_counselor_and_create_session(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);
        $counselor = User::factory()->create([
            'role' => 'guru_bk',
            'is_ready' => true,
            'spesialisasi_kategori' => 'Belajar',
        ]);

        $category = Category::where('name', 'Belajar')->first();

        // Setup session data
        $response = $this->actingAs($student)
            ->withSession([
                'draft_complaint' => 'Saya kesulitan fokus saat belajar matematika.',
                'ai_category_id' => $category->id,
                'ai_category_name' => $category->name,
                'ai_summary' => 'Siswa kesulitan belajar matematika.',
                'ai_provider' => 'Smart NLP Engine',
            ])
            ->post(route('student.sessions.create'), [
                'counselor_id' => $counselor->id,
            ]);

        $response->assertRedirect(route('student.dashboard'));

        $this->assertDatabaseHas('counseling_sessions', [
            'student_id' => $student->id,
            'counselor_id' => $counselor->id,
            'status' => 'pending',
        ]);
    }

    public function test_counselor_can_toggle_ready_status(): void
    {
        $counselor = User::factory()->create([
            'role' => 'guru_bk',
            'is_ready' => false,
        ]);

        $response = $this->actingAs($counselor)->post(route('counselor.toggle-ready'));
        $response->assertSessionHas('success');

        $this->assertTrue($counselor->fresh()->is_ready);
    }

    public function test_counselor_can_accept_and_complete_session(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);
        $counselor = User::factory()->create(['role' => 'guru_bk', 'is_ready' => true]);
        $category = Category::first();

        $session = CounselingSession::create([
            'student_id' => $student->id,
            'counselor_id' => $counselor->id,
            'ai_category_id' => $category->id,
            'original_complaint' => 'Saya butuh bantuan untuk belajar efektif.',
            'ai_summary' => 'Siswa butuh bimbingan belajar.',
            'status' => 'pending',
        ]);

        // Counselor accepts
        $response = $this->actingAs($counselor)->post(route('counselor.sessions.accept', $session->id));
        $response->assertSessionHas('success');
        $this->assertEquals('accepted', $session->fresh()->status);

        // Counselor completes
        $completeResponse = $this->actingAs($counselor)->post(route('counselor.sessions.complete', $session->id), [
            'counselor_notes' => 'Siswa diberikan panduan belajar teknik Feynman.',
        ]);

        $completeResponse->assertSessionHas('success');
        $this->assertEquals('completed', $session->fresh()->status);
        $this->assertEquals('Siswa diberikan panduan belajar teknik Feynman.', $session->fresh()->counselor_notes);
    }
}
