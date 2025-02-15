<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Manga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class CommentController
 * 
 * Gère les fonctionnalités liées aux commentaires, y compris leur ajout,
 * modification, suppression et les réponses.
 */
class CommentController extends Controller
{
    /**
     * Enregistre un commentaire pour un manga.
     *
     * @param Request $request La requête HTTP contenant le contenu du commentaire.
     * @param Manga $manga Le manga auquel le commentaire est associé.
     * @return \Illuminate\Http\RedirectResponse Redirige vers la page du manga avec un message de succès.
     */
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

    /**
     * Affiche le formulaire d'édition d'un commentaire.
     *
     * @param Comment $comment Le commentaire à modifier.
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse La vue du formulaire ou redirection en cas d'erreur.
     */
    public function edit(Comment $comment)
    {
        if ($comment->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403); // Modification ici : Retourne une erreur 403 au lieu d'une redirection
        }

        return view('comments.edit', compact('comment'));
    }

    /**
     * Met à jour un commentaire.
     *
     * @param Request $request La requête HTTP contenant les nouvelles données du commentaire.
     * @param Comment $comment Le commentaire à mettre à jour.
     * @return \Illuminate\Http\RedirectResponse Redirige vers la page du manga associé avec un message de succès.
     */
    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403); // Modification ici : Retourne une erreur 403 au lieu d'une redirection
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $request->content,
        ]);

        return redirect()->route('mangas.show', $comment->manga)->with('success', 'Commentaire mis à jour avec succès !');
    }

    /**
     * Supprime un commentaire.
     *
     * @param Comment $comment Le commentaire à supprimer.
     * @return \Illuminate\Http\RedirectResponse Redirige vers la page du manga associé avec un message de succès.
     */
    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403); // Modification ici : Retourne une erreur 403 au lieu d'une redirection
        }

        $comment->delete();

        return redirect()->route('mangas.show', $comment->manga)->with('success', 'Commentaire supprimé avec succès !');
    }

    /**
     * Enregistre une réponse à un commentaire.
     *
     * @param Request $request La requête HTTP contenant le contenu de la réponse.
     * @param Comment $comment Le commentaire parent auquel répondre.
     * @return \Illuminate\Http\RedirectResponse Redirige vers la page du manga associé avec un message de succès.
     */
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
