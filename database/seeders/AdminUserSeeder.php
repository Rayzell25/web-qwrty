<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@rpd.local'],
            [
                'name' => 'Administrator RPD',
                'whatsapp' => '081200000000',
                'password' => Hash::make('password123'),
                'is_admin' => true,
                'is_active' => true,
                'otp_verified_at' => Carbon::now(),
                'email_verified_at' => Carbon::now(),
            ]
        );

        // A regular verified demo user.
        User::updateOrCreate(
            ['email' => 'user@rpd.local'],
            [
                'name' => 'Demo User',
                'whatsapp' => '081200000001',
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'is_active' => true,
                'otp_verified_at' => Carbon::now(),
                'email_verified_at' => Carbon::now(),
            ]
        );
    }
}
