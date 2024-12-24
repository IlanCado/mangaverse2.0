<?php

namespace App\Http\Controllers;

use App\Models\Manga;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    // Ajouter ou mettre à jour une note
    public function store(Request $request, Manga $manga)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Vérifie si l'utilisateur a déjà noté le manga
        $rating = Rating::updateOrCreate(
            ['user_id' => Auth::id(), 'manga_id' => $manga->id],
            ['rating' => $request->rating]
        );

        return redirect()->route('mangas.show', $manga)->with('success', 'Votre note a été enregistrée.');
    }
}
