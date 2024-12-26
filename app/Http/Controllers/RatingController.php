<?php

namespace App\Http\Controllers;

use App\Models\Manga;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class RatingController
 *
 * Gère les actions liées aux notes des mangas, telles que l'ajout et la mise à jour des évaluations.
 */
class RatingController extends Controller
{
    /**
     * Ajoute ou met à jour une note pour un manga spécifique.
     *
     * @param Request $request La requête HTTP contenant les données de la note.
     * @param Manga $manga L'instance du manga pour lequel la note est donnée.
     * @return \Illuminate\Http\RedirectResponse Redirection vers la page du manga avec un message de succès.
     */
    public function store(Request $request, Manga $manga)
    {
        // Valide la note fournie
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Ajoute ou met à jour la note pour l'utilisateur et le manga
        $rating = Rating::updateOrCreate(
            ['user_id' => Auth::id(), 'manga_id' => $manga->id],
            ['rating' => $request->rating]
        );

        return redirect()->route('mangas.show', $manga)->with('success', 'Votre note a été enregistrée.');
    }
}
