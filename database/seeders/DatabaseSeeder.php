<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Manga;
use App\Models\Comment;
use App\Models\Rating;
use Illuminate\Database\Seeder;

/**
 * Seeder principal pour l'application Mangaverse.
 *
 * Ce seeder remplit la base de données avec :
 * - 1 utilisateur administrateur.
 * - 10 utilisateurs classiques.
 * - 1 utilisateur de test spécifique.
 * - 50 mangas.
 * - Des commentaires et notes aléatoires pour chaque manga.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Exécute les insertions dans la base de données.
     *
     * Cette méthode crée tous les jeux de données nécessaires pour simuler une application complète,
     * avec utilisateurs, mangas, commentaires et notes.
     *
     * @return void
     */
    public function run(): void
    {
        // Création d'un compte administrateur.
        $admin = User::factory()->admin()->create([
            'name' => 'Admin Mangaverse',
            'email' => 'admin@admin.com',
            'password' => bcrypt('adminadmin'),
        ]);

        // Création de 10 utilisateurs classiques.
        $users = User::factory(10)->create();

        // Création d'un utilisateur de test.
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@user.com',
            'password' => bcrypt('useruser'),
        ]);

        // Combine tous les utilisateurs (admin, classiques, test).
        $allUsers = $users->concat([$admin, $testUser]);

        // Création de 50 mangas avec assignation dynamique du user_id.
        $mangas = Manga::factory(50)->make();

        foreach ($mangas as $manga) {
            $manga->user_id = $allUsers->random()->id; // Utilisateur aléatoire à chaque manga
            $manga->save();
        }

        // Ajout de commentaires et notes pour chaque manga.
        foreach ($mangas as $manga) {
            // Ajoute entre 2 et 5 commentaires.
            Comment::factory(rand(2, 5))->create([
                'manga_id' => $manga->id,
                'user_id' => $allUsers->random()->id,
            ]);

            // Ajoute entre 3 et 10 notes.
            Rating::factory(rand(3, 10))->create([
                'manga_id' => $manga->id,
                'user_id' => $allUsers->random()->id,
            ]);
        }
    }
}
