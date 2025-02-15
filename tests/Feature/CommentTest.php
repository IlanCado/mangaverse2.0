<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Manga;
use App\Models\User;
use App\Models\Comment;

class CommentTest extends TestCase
{
    use RefreshDatabase; // Réinitialise la base de données à chaque test

    /** @test */
    public function a_guest_cannot_post_a_comment()
    {
        // Création d'un manga
        $manga = Manga::factory()->create();

        //Tentative de création d'un commentaire sans être connecté
        $response = $this->post("/mangas/{$manga->id}/comments", [
            'content' => 'Super manga !',
        ]);

        //Vérification que l'utilisateur est redirigé vers la page de connexion
        $response->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_post_a_comment()
    {
        //Création d'un utilisateur et d'un manga
        $user = User::factory()->create();
        $manga = Manga::factory()->create();

        //Connexion de l'utilisateur
        $this->actingAs($user);

        //Envoi de la requête pour poster un commentaire
        $response = $this->post("/mangas/{$manga->id}/comments", [
            'content' => 'Super manga !',
        ]);

        //Vérifier la redirection après l'ajout du commentaire
        $response->assertStatus(302);

        //Vérifier que le commentaire est bien en base de données
        $this->assertDatabaseHas('comments', [
            'content' => 'Super manga !',
            'user_id' => $user->id,
            'manga_id' => $manga->id,
        ]);
    }

    /** @test */
    public function a_user_cannot_edit_someone_elses_comment()
    {
        //Création de deux utilisateurs et d'un manga
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $manga = Manga::factory()->create();

        //Création d'un commentaire par l'utilisateur A
        $comment = Comment::factory()->create([
            'user_id' => $userA->id,
            'manga_id' => $manga->id,
            'content' => 'Commentaire original',
        ]);

        //Connexion de l'utilisateur B (qui ne doit pas pouvoir modifier le commentaire)
        $this->actingAs($userB);

        //Tentative de modification du commentaire
        $response = $this->put("/comments/{$comment->id}", [
            'content' => 'Tentative de modification',
        ]);

        //Vérifier que l'utilisateur B reçoit une erreur d'acces
        $response->assertStatus(403);

        //Vérifier que le contenu du commentaire n'a pas changé
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => 'Commentaire original', // Toujours l'ancien contenu
        ]);
    }

    /** @test */
public function a_user_can_delete_their_own_comment()
{
    // Création d'un utilisateur et d'un manga
    $user = User::factory()->create();
    $manga = Manga::factory()->create();

    // Création d'un commentaire par cet utilisateur
    $comment = Comment::factory()->create([
        'user_id' => $user->id,
        'manga_id' => $manga->id,
    ]);

    // Connexion de l'utilisateur
    $this->actingAs($user);

    // Tentative de suppression du commentaire
    $response = $this->delete("/comments/{$comment->id}");

    // Vérifier que la suppression s'est bien passée
    $response->assertStatus(302); // Redirection après suppression

    // Vérifier que le commentaire n'existe plus en base
    $this->assertDatabaseMissing('comments', [
        'id' => $comment->id,
    ]);
}

/** @test */
public function a_user_cannot_delete_someone_elses_comment()
{
    // Création de deux utilisateurs et d'un manga
    $userA = User::factory()->create();
    $userB = User::factory()->create();
    $manga = Manga::factory()->create();

    // Création d'un commentaire par l'utilisateur A
    $comment = Comment::factory()->create([
        'user_id' => $userA->id,
        'manga_id' => $manga->id,
    ]);

    // Connexion de l'utilisateur B (qui ne doit pas pouvoir supprimer le commentaire)
    $this->actingAs($userB);

    // Tentative de suppression du commentaire
    $response = $this->delete("/comments/{$comment->id}");

    // Vérifier que l'utilisateur reçoit une interdiction (403)
    $response->assertStatus(403);

    // Vérifier que le commentaire est toujours en base
    $this->assertDatabaseHas('comments', [
        'id' => $comment->id,
    ]);
}


}
