<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Manga;

class MangaSeeder extends Seeder
{
    public function run()
    {
        // Crée 50 mangas aléatoires
        Manga::factory()->count(50)->create();
    }
}
