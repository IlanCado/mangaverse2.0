<?php

namespace Database\Factories;

use App\Models\Rating;
use App\Models\User;
use App\Models\Manga;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory pour générer des enregistrements de notes pour le modèle Rating.
 *
 * Cette factory crée des notes aléatoires pour des mangas,
 * chaque note étant associée à un utilisateur et un manga existants.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rating>
 */
class RatingFactory extends Factory
{
    /**
     * Le modèle associé à cette factory.
     *
     * @var string
     */
    protected $model = Rating::class;

    /**
     * Définit l'état par défaut des attributs pour le modèle Rating.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rating' => $this->faker->numberBetween(1, 5), // Note entre 1 et 5
            'user_id' => User::factory(), // Associe la note à un utilisateur aléatoire
            'manga_id' => Manga::factory(), // Associe la note à un manga aléatoire
        ];
    }
}
