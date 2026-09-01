<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * A real, documented admin account (README) plus a handful of client
 * accounts — every seeded password is "password". This replaces the
 * default scaffold's single unnamed "Test User" (role client, no way to
 * reach the admin views without a manual `tinker` promotion) with an
 * account a recruiter can actually log into and see the admin side of the
 * app immediately.
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@nextlevelgaming.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        foreach ([
            ['Sarah Chen', 'sarah.chen@example.com'],
            ['Marcus Johnson', 'marcus.j@example.com'],
            ['Priya Patel', 'priya.p@example.com'],
            ['Tom Becker', 'tom.becker@example.com'],
        ] as [$name, $email]) {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password'),
                'role' => 'client',
                'email_verified_at' => now(),
            ]);
        }
    }
}
