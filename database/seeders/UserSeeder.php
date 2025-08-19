<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * UserSeeder creates test users with the following credentials:
 * 
 * Admin Users:
 * - admin@example.com / admin123
 * - john.admin@example.com / johnadmin123
 * 
 * Manager Users:
 * - manager@example.com / manager123
 * - jane.manager@example.com / janemanager123
 * 
 * Regular Users:
 * - user@example.com / user123
 * - Random users (10) / password
 */
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
        ]);

        // Create manager user
        User::factory()->manager()->create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('manager123'),
        ]);

        // Create regular user
        User::factory()->user()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => Hash::make('user123'),
        ]);

        // Create additional test users with different roles
        User::factory()->admin()->create([
            'name' => 'John Admin',
            'email' => 'john.admin@example.com',
            'password' => Hash::make('johnadmin123'),
        ]);

        User::factory()->manager()->create([
            'name' => 'Jane Manager',
            'email' => 'jane.manager@example.com',
            'password' => Hash::make('janemanager123'),
        ]);

        // Create additional random users (default role: user)
        // These will use the default factory password (password)
        User::factory(10)->create();
    }
}
