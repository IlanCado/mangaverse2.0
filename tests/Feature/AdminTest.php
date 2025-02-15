<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Manga;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_admin_can_validate_a_manga()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $manga = Manga::factory()->create(['is_validated' => false]);

        $this->actingAs($admin);
        $response = $this->post("/admin/mangas/{$manga->id}/validate");

        $response->assertRedirect();
        $this->assertDatabaseHas('mangas', [
            'id' => $manga->id,
            'is_validated' => true,
        ]);
    }

    /** @test */
    public function a_non_admin_cannot_validate_a_manga()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $manga = Manga::factory()->create(['is_validated' => false]);

        $this->actingAs($user);
        $response = $this->post("/admin/mangas/{$manga->id}/validate");

        $response->assertStatus(403);
        $this->assertDatabaseHas('mangas', [
            'id' => $manga->id,
            'is_validated' => false,
        ]);
    }

    /** @test */
    public function an_admin_can_delete_a_manga()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $manga = Manga::factory()->create();

        $this->actingAs($admin);
        $response = $this->delete("/admin/mangas/{$manga->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('mangas', [
            'id' => $manga->id,
        ]);
    }

    /** @test */
    public function a_non_admin_cannot_delete_a_manga()
    {
        $user = User::factory()->create(['is_admin' => false]);
        $manga = Manga::factory()->create();

        $this->actingAs($user);
        $response = $this->delete("/admin/mangas/{$manga->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('mangas', [
            'id' => $manga->id,
        ]);
    }
}
