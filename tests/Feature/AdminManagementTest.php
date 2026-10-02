<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CounselingSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
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
    }

    public function test_admin_can_access_dashboard_and_management_pages(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.users.index'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.categories.index'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.sessions.index'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.settings'))->assertStatus(200);
    }

    public function test_non_admin_cannot_access_admin_routes(): void
    {
        $student = User::factory()->create(['role' => 'siswa']);
        $counselor = User::factory()->create(['role' => 'guru_bk']);

        $this->actingAs($student)->get(route('admin.dashboard'))->assertRedirect(route('student.dashboard'));
        $this->actingAs($counselor)->get(route('admin.dashboard'))->assertRedirect(route('counselor.dashboard'));
    }

    public function test_admin_can_create_and_delete_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Create Guru BK
        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Guru Baru BK',
            'email' => 'guru.baru@temanbk.sch.id',
            'role' => 'guru_bk',
            'password' => 'password123',
            'nip' => '199501012020011005',
            'spesialisasi_kategori' => 'Belajar',
            'is_ready' => 1,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', ['email' => 'guru.baru@temanbk.sch.id']);

        $createdUser = User::where('email', 'guru.baru@temanbk.sch.id')->first();
        $this->actingAs($admin)->delete(route('admin.users.delete', $createdUser->id));
        $this->assertDatabaseMissing('users', ['id' => $createdUser->id]);
    }

    public function test_admin_can_create_category_and_reassign_session(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'siswa']);
        $counselor1 = User::factory()->create(['role' => 'guru_bk']);
        $counselor2 = User::factory()->create(['role' => 'guru_bk']);
        $category = Category::first();

        // Create Category
        $catResponse = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Kedisiplinan',
            'description' => 'Masalah tata tertib',
            'badge_color' => 'teal',
        ]);
        $catResponse->assertSessionHas('success');
        $this->assertDatabaseHas('categories', ['name' => 'Kedisiplinan']);

        // Create Session & Reassign
        $session = CounselingSession::create([
            'student_id' => $student->id,
            'counselor_id' => $counselor1->id,
            'ai_category_id' => $category->id,
            'original_complaint' => 'Saya sering terlambat sekolah.',
            'ai_summary' => 'Masalah kedisiplinan waktu.',
            'status' => 'pending',
        ]);

        $reassignResponse = $this->actingAs($admin)->post(route('admin.sessions.reassign', $session->id), [
            'counselor_id' => $counselor2->id,
        ]);

        $reassignResponse->assertSessionHas('success');
        $this->assertEquals($counselor2->id, $session->fresh()->counselor_id);
    }
}
