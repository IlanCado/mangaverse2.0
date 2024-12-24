<?php

namespace Database\Factories;

use App\Models\Manga;
use Illuminate\Database\Eloquent\Factories\Factory;

class MangaFactory extends Factory
{
    protected $model = Manga::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3), // Titre de 3 mots
            'description' => $this->faker->paragraph(4), // Description de 4 phrases
            'genre' => $this->faker->randomElement(['Action', 'Aventure', 'Comédie', 'Drame', 'Fantastique', 'Horreur', 'Romance', 'Science-Fiction']),
            'author' => $this->faker->name, // Nom de l'auteur
            'user_id' => \App\Models\User::factory(), // Associe à un utilisateur aléatoire
        ];
    }
}
