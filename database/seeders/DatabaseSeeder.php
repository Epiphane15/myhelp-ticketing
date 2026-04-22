<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Define priorities
        \App\Models\Priority::create(['name' => 'Basse', 'color' => '#28a745']);
        \App\Models\Priority::create(['name' => 'Normale', 'color' => '#17a2b8']);
        \App\Models\Priority::create(['name' => 'Haute', 'color' => '#ffc107']);
        \App\Models\Priority::create(['name' => 'Urgente', 'color' => '#dc3545']);

        // Create Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create Agent user
        User::create([
            'name' => 'Agent Support',
            'email' => 'agent@example.com',
            'password' => bcrypt('password'),
            'role' => 'agent',
        ]);

        // Create test Client user
        User::create([
            'name' => 'Test Client',
            'email' => 'client@example.com',
            'password' => bcrypt('password'),
            'role' => 'client',
        ]);
    }
}
