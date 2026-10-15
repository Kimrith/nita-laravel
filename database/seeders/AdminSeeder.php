<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Role;
use App\Models\ShiftPattern;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create or update the default administrator user
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('Admin@123456'),
                'role' => 'admin',
                'status' => 'approved',
                'email_verified_at' => now(),
            ]
        );

        // 2. Ensure an associated Employee profile exists for the Admin
        $adminRole = Role::firstOrCreate(
            ['role_name' => 'Admin'],
            ['access_level' => 3]
        );

        $shift = ShiftPattern::first();

        Employee::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'user_id' => $admin->id,
                'full_name' => 'System Administrator',
                'role_id' => $adminRole->role_id,
                'shift_pattern_id' => $shift?->shift_pattern_id,
                'hire_date' => now()->subYears(2)->toDateString(),
                'profile_image' => Employee::DEFAULT_AVATAR,
                'is_active' => true,
            ]
        );
    }
}
