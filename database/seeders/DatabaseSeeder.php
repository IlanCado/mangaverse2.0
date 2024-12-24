<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Manga;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crée 10 utilisateurs
        $users = User::factory(10)->create();

        // Crée un utilisateur spécifique pour tester
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Crée 50 mangas en associant aléatoirement les utilisateurs
        Manga::factory(50)->create([
            'user_id' => $users->random()->id,
        ]);
    }
}
