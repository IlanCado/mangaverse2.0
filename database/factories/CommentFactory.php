<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use App\Models\Manga;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'content' => $this->faker->sentence(),
            'user_id' => User::factory(),   // Associe à un utilisateur aléatoire
            'manga_id' => Manga::factory(), // Associe à un manga aléatoire
            'parent_id' => null,            // Peut être null ou un autre commentaire
        ];
    }
}
