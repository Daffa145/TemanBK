<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('student.dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_demo_student_can_login_and_redirect_to_student_dashboard(): void
    {
        $student = User::factory()->create([
            'email' => 'siswa@temanbk.sch.id',
            'role' => 'siswa',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'siswa@temanbk.sch.id',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($student);
        $response->assertRedirect(route('student.dashboard', absolute: false));
    }

    public function test_demo_counselor_can_login_and_redirect_to_counselor_dashboard(): void
    {
        $counselor = User::factory()->create([
            'email' => 'budi.bk@temanbk.sch.id',
            'role' => 'guru_bk',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'budi.bk@temanbk.sch.id',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($counselor);
        $response->assertRedirect(route('counselor.dashboard', absolute: false));
    }

    public function test_login_rate_limiting_locks_out_after_multiple_failed_attempts(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-pass',
            ]);
        }

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-pass',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_login_fails_if_honeypot_is_filled(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'hp_field' => 'bot-submitted-value',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('hp_field');
    }
}
