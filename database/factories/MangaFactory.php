<?php

namespace Database\Factories;

use App\Models\Manga;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Fabrique permettant de générer des mangas pour le modèle Manga.
 *
 * Cette factory crée des mangas aléatoires avec des données réalistes.
 * Chaque manga est lié à un utilisateur aléatoire.
 * Il peut être validé ou non (aléatoire).
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Manga>
 */
class MangaFactory extends Factory
{
    /**
     * Le nom du modèle associé à cette factory.
     *
     * @var string
     */
    protected $model = Manga::class;

    /**
     * Définit l'état par défaut du modèle Manga.
     *
     * Chaque manga est lié à un utilisateur aléatoire.
     * Il peut être validé ou non (probabilité 50%).
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3), // Titre aléatoire de 3 mots
            'description' => $this->faker->paragraph(4), // Description aléatoire de 4 phrases
            'genre' => $this->faker->randomElement([
                'Action', 'Aventure', 'Comédie', 'Drame', 'Fantaisie', 
                'Horreur', 'Romance', 'Science-Fiction', 'Slice of Life', 
                'Thriller', 'Mystère', 'Mecha'
            ]), // Genre aléatoire parmi une liste
            'author' => $this->faker->name(), // Auteur aléatoire
            'user_id' => User::factory(), // Associe à un utilisateur aléatoire
            'is_validated' => $this->faker->boolean(50), // 50% de chances d'être validé
            'image_path' => null, // Pas d'image par défaut
        ];
    }
}
