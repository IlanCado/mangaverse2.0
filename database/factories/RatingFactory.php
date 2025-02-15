<?php

namespace Database\Factories;

use App\Models\Rating;
use App\Models\User;
use App\Models\Manga;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingFactory extends Factory
{
    protected $model = Rating::class;

    public function definition()
    {
        return [
            'rating' => $this->faker->numberBetween(1, 5), // Note entre 1 et 5
            'user_id' => User::factory(),
            'manga_id' => Manga::factory(),
        ];
    }
}
