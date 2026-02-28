<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin user
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@dubaitravel.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => UserRole::SUPER_ADMIN,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info("Super Admin created: {$superAdmin->email}");

        // Create a regular Admin user with Hajj section access
        $hajjAdmin = User::updateOrCreate(
            ['email' => 'hajj.admin@dubaitravel.com'],
            [
                'name' => 'Hajj Admin',
                'password' => Hash::make('password'),
                'role' => UserRole::ADMIN,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Assign Hajj section to admin
        $hajjAdmin->assignedSections()->updateOrCreate(
            ['section' => 'hajj'],
            ['assigned_by' => $superAdmin->id]
        );

        $this->command->info("Hajj Admin created: {$hajjAdmin->email}");

        // Create a demo customer user
        $demoUser = User::updateOrCreate(
            ['email' => 'user@dubaitravel.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
                'role' => UserRole::USER,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info("Demo User created: {$demoUser->email}");
    }
}
