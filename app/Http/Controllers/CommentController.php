<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Manga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Enregistrer un commentaire
    public function store(Request $request, Manga $manga)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Comment::create([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'manga_id' => $manga->id,
        ]);

        return redirect()->route('mangas.show', $manga)->with('success', 'Commentaire ajouté avec succès !');
    }

    // Afficher le formulaire d'édition d'un commentaire
    public function edit(Comment $comment)
    {
        if ($comment->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return redirect()->back()->with('error', 'Vous n\'avez pas la permission.');
        }

        return view('comments.edit', compact('comment'));
    }

    // Mettre à jour un commentaire
    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return redirect()->back()->with('error', 'Vous n\'avez pas la permission.');
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $request->content,
        ]);

        return redirect()->route('mangas.show', $comment->manga)->with('success', 'Commentaire mis à jour avec succès !');
    }

    // Supprimer un commentaire
    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return redirect()->back()->with('error', 'Vous n\'avez pas la permission.');
        }

        $comment->delete();

        return redirect()->route('mangas.show', $comment->manga)->with('success', 'Commentaire supprimé avec succès !');
    }

    public function replyStore(Request $request, Comment $comment)
{
    // Valider le contenu de la réponse
    $request->validate([
        'content' => 'required|string|max:1000',
    ]);

    // Créer une réponse au commentaire
    Comment::create([
        'content' => $request->content,
        'user_id' => Auth::id(),
        'manga_id' => $comment->manga_id, // ID du manga associé
        'parent_id' => $comment->id, // ID du commentaire parent
    ]);

    return redirect()->route('mangas.show', $comment->manga)->with('success', 'Réponse ajoutée avec succès !');
}

}
