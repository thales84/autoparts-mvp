<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ─── Register ─────────────────────────────────────────────────────────────

    public function test_register_page_loads(): void
    {
        $this->get(route('register'))->assertStatus(200);
    }

    public function test_user_can_register(): void
    {
        $this->post(route('register'), [
            'name'                  => 'Jean Dupont',
            'email'                 => 'jean@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', ['email' => 'jean@example.com', 'role' => 'customer']);
        $this->assertAuthenticated();
    }

    public function test_register_fails_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $this->post(route('register'), [
            'name'                  => 'Jean Dupont',
            'email'                 => 'existing@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('email');
    }

    public function test_register_fails_with_short_password(): void
    {
        $this->post(route('register'), [
            'name'                  => 'Jean Dupont',
            'email'                 => 'jean@example.com',
            'password'              => '1234',
            'password_confirmation' => '1234',
        ])->assertSessionHasErrors('password');
    }

    // ─── Login ────────────────────────────────────────────────────────────────

    public function test_login_page_loads(): void
    {
        $this->get(route('login'))->assertStatus(200);
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create(['status' => 'active']);

        $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create();

        $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'wrongpassword',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_blocked_user_cannot_login(): void
    {
        $user = User::factory()->create(['status' => 'blocked']);

        $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    // ─── Logout ───────────────────────────────────────────────────────────────

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect();

        $this->assertGuest();
    }
}
