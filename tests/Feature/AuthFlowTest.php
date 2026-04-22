<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_login(): void
    {
        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'VerySecure123',
            'password_confirmation' => 'VerySecure123',
        ])->assertRedirect('/email/verify');

        auth()->logout();

        $this->post('/login', ['email' => 'test@example.com', 'password' => 'VerySecure123'])
            ->assertRedirect('/dashboard');
    }

    public function test_blocked_user_is_logged_out(): void
    {
        $user = User::factory()->create(['is_blocked' => true]);
        $this->actingAs($user)->get('/dashboard')->assertRedirect('/login');
    }
}
