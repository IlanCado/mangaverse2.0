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
        $users = User::all();
        $mangasPending = Manga::where('is_validated', false)->with('user')->get();
        $mangasValidated = Manga::where('is_validated', true)->with('user')->get();

        return view('admin.index', compact('users', 'mangasPending', 'mangasValidated'));
    }

    /**
     * Supprime un manga.
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
     */
    public function destroyUser(User $user)
    {
        $user->delete();

        return redirect()->route('admin.index')->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Valide un manga et ajoute une image si elle est fournie.
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
     */
    public function editManga(Manga $manga)
    {
        return view('admin.edit', compact('manga'));
    }

    /**
     * Met à jour les informations d'un manga validé.
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
