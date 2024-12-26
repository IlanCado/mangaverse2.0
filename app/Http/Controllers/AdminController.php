<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Manga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class AdminController
 * 
 * Gère les fonctionnalités d'administration, y compris la validation, la suppression,
 * et la mise à jour des mangas, ainsi que la gestion des utilisateurs.
 */
class AdminController extends Controller
{
    /**
     * Affiche la page d'administration.
     *
     * Récupère les utilisateurs, les mangas en attente de validation et les mangas validés.
     *
     * @return \Illuminate\Contracts\View\View La vue d'administration.
     */
    public function index()
    {
        $users = User::all();
        $mangasPending = Manga::where('is_validated', false)->with('user')->get();
        $mangasValidated = Manga::where('is_validated', true)->with('user')->get();

        return view('admin.index', compact('users', 'mangasPending', 'mangasValidated'));
    }

    /**
     * Supprime un manga validé ou en attente.
     *
     * Supprime également l'image associée du stockage si elle existe.
     *
     * @param Manga $manga Le manga à supprimer.
     * @return \Illuminate\Http\RedirectResponse Redirige vers la page d'administration.
     */
    public function destroyManga(Manga $manga)
    {
        if ($manga->image_path) {
            Storage::disk('public')->delete($manga->image_path);
        }

        $manga->delete();

        return redirect()->route('admin.index')->with('success', 'Manga supprimé avec succès.');
    }

    /**
     * Supprime un utilisateur.
     *
     * @param User $user L'utilisateur à supprimer.
     * @return \Illuminate\Http\RedirectResponse Redirige vers la page d'administration.
     */
    public function destroyUser(User $user)
    {
        $user->delete();

        return redirect()->route('admin.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Valide un manga et, si fourni, ajoute une image.
     *
     * @param Request $request La requête HTTP contenant les données de validation.
     * @param Manga $manga Le manga à valider.
     * @return \Illuminate\Http\RedirectResponse Redirige vers la page d'administration.
     */
    public function validateManga(Request $request, Manga $manga)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        if ($request->hasFile('image')) {
            if ($manga->image_path) {
                Storage::disk('public')->delete($manga->image_path);
            }

            $imagePath = $request->file('image')->store('manga_images', 'public');
            $manga->image_path = $imagePath;
        }

        $manga->is_validated = true;
        $manga->save();

        return redirect()->route('admin.index')->with('success', 'Manga validé avec succès !');
    }

    /**
     * Affiche le formulaire pour modifier un manga validé.
     *
     * @param Manga $manga Le manga à modifier.
     * @return \Illuminate\Contracts\View\View La vue de modification du manga.
     */
    public function editManga(Manga $manga)
    {
        return view('admin.edit', compact('manga'));
    }

    /**
     * Met à jour les informations d'un manga validé.
     *
     * @param Request $request La requête HTTP contenant les nouvelles données du manga.
     * @param Manga $manga Le manga à mettre à jour.
     * @return \Illuminate\Http\RedirectResponse Redirige vers la page d'administration.
     */
    public function updateManga(Request $request, Manga $manga)
    {
        $request->validate([
            'title' => 'required|string|min:3|max:255',
            'author' => 'required|string|min:3|max:255',
            'genre' => 'required|string|in:' . implode(',', Manga::GENRES),
            'description' => 'required|string|min:10|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        if ($request->hasFile('image')) {
            if ($manga->image_path) {
                Storage::disk('public')->delete($manga->image_path);
            }

            $imagePath = $request->file('image')->store('manga_images', 'public');
            $manga->image_path = $imagePath;
        }

        $manga->update([
            'title' => $request->title,
            'author' => $request->author,
            'genre' => $request->genre,
            'description' => $request->description,
            'image_path' => $manga->image_path ?? $manga->image_path,
        ]);

        return redirect()->route('admin.index')->with('success', 'Manga modifié avec succès.');
    }
}
