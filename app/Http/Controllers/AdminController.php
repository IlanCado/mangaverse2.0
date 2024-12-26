<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Manga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Affiche la page d'administration avec les mangas en attente et validés.
     */
    public function index()
    {
        // Récupérer tous les utilisateurs et séparer les mangas en attente et validés
        $users = User::all();
        $mangasPending = Manga::where('is_validated', false)->with('user')->get();
        $mangasValidated = Manga::where('is_validated', true)->with('user')->get();

        return view('admin.index', compact('users', 'mangasPending', 'mangasValidated'));
    }

    /**
     * Supprime un manga.
     *
     * @param Manga $manga
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyManga(Manga $manga)
    {
        // Supprime l'image associée si elle existe
        if ($manga->image_path) {
            Storage::disk('public')->delete($manga->image_path);
        }

        $manga->delete();

        return redirect()->route('admin.index')->with('success', 'Manga supprimé avec succès.');
    }

    /**
     * Supprime un utilisateur.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyUser(User $user)
    {
        // Supprime l'utilisateur
        $user->delete();

        return redirect()->route('admin.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Valide un manga et ajoute une image si elle est fournie.
     *
     * @param Request $request
     * @param Manga $manga
     * @return \Illuminate\Http\RedirectResponse
     */
    public function validateManga(Request $request, Manga $manga)
    {
        // Validation de l'image si elle est fournie
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        if ($request->hasFile('image')) {
            // Supprime l'image précédente si elle existe
            if ($manga->image_path) {
                Storage::disk('public')->delete($manga->image_path);
            }

            // Enregistre la nouvelle image
            $imagePath = $request->file('image')->store('manga_images', 'public');
            $manga->image_path = $imagePath;
        }

        // Valide le manga
        $manga->is_validated = true;
        $manga->save();

        return redirect()->route('admin.index')->with('success', 'Manga validé avec succès !');
    }
}
