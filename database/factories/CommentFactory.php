<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use App\Models\Manga;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Fabrique permettant de générer des commentaires pour le modèle Comment.
 *
 * Cette factory crée des commentaires aléatoires, chacun associé à :
 * - Un utilisateur aléatoire.
 * - Un manga aléatoire.
 * 
 * Les commentaires générés sont par défaut des commentaires principaux
 * (ils n'ont pas de parent), mais ce champ peut être modifié pour les réponses.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Le modèle associé à cette factory.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Définit l'état par défaut du modèle Comment.
     *
     * Chaque commentaire est lié à un utilisateur et un manga aléatoires.
     * Il s'agit par défaut d'un commentaire principal (sans parent).
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => $this->faker->sentence(), // Contenu aléatoire du commentaire
            'user_id' => User::factory(),          // Associe un utilisateur aléatoire
            'manga_id' => Manga::factory(),        // Associe un manga aléatoire
            'parent_id' => null,                   // Par défaut, pas de parent (commentaire principal)
        ];
    }
}
