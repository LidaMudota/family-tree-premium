<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestLoginUserSeeder extends Seeder
{
    /**
     * Данные тестового аккаунта для входа (не выводятся во фронтенд):
     * Email: test.user@example.com
     * Password: TestUserPass123!
     */
    public function run(): void
    {
        $email = 'test.user@example.com';
        $password = 'TestUserPass123!';

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Test User',
                'password' => Hash::make($password),
                'role' => 'user',
                'is_blocked' => false,
                'blocked_at' => null,
                'blocked_reason' => null,
                'email_verified_at' => now(),
            ]
        );

        if ($this->command !== null) {
            $this->command->info('Тестовый аккаунт для входа обновлён/создан.');
            $this->command->line('Email: test.user@example.com');
            $this->command->line('Password: TestUserPass123!');
        }
    }
}
