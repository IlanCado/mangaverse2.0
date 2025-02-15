<?php

namespace Tests\Unit;

use App\Models\Manga;
use App\Models\Comment;
use App\Models\Rating;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MangaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_manga_has_many_comments()
    {
        $manga = Manga::factory()->create();
        Comment::factory(3)->create(['manga_id' => $manga->id]);

        $this->assertCount(3, $manga->comments);
    }

    /** @test */
    public function a_manga_has_many_ratings()
    {
        $manga = Manga::factory()->create();
        Rating::factory(2)->create(['manga_id' => $manga->id]);

        $this->assertCount(2, $manga->ratings);
    }

    /** @test */
public function deleting_a_manga_deletes_its_comments_and_ratings()
{
    $manga = Manga::factory()->create();
    Comment::factory(3)->create(['manga_id' => $manga->id]);
    Rating::factory(2)->create(['manga_id' => $manga->id]);

    $manga->delete();

    $this->assertDatabaseMissing('mangas', ['id' => $manga->id]);
    $this->assertDatabaseMissing('comments', ['manga_id' => $manga->id]);
    $this->assertDatabaseMissing('ratings', ['manga_id' => $manga->id]);
}

/** @test */
public function a_manga_can_calculate_its_average_rating()
{
    $manga = Manga::factory()->create();
    Rating::factory()->create(['manga_id' => $manga->id, 'rating' => 4]);
    Rating::factory()->create(['manga_id' => $manga->id, 'rating' => 2]);

    $this->assertEquals(3, $manga->averageRating());
}

}
