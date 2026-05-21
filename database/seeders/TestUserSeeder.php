<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'client@test.local'],
            [
                'name'     => 'Jean Dupont',
                'phone'    => '699000001',
                'password' => Hash::make('Client1234!'),
                'role'     => 'customer',
                'status'   => 'active',
            ]
        );
    }
}
