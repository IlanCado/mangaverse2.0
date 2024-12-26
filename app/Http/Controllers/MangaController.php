<?php

namespace App\Http\Controllers;

use App\Models\Manga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MangaController extends Controller
{
    // Affiche tous les mangas validés avec recherche avancée et filtres
    public function index(Request $request)
    {
        $query = Manga::where('is_validated', true); // Affiche uniquement les mangas validés

        // Recherche avancée par mots-clés
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('author', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filtrer par genre
        if ($request->filled('genre')) {
            $query->where('genre', $request->genre);
        }

        // Pagination des résultats
        $mangas = $query->paginate(10);

        return view('mangas.index', compact('mangas'));
    }

    // Affiche le formulaire pour créer un manga
    public function create()
    {
        return view('mangas.create');
    }

    // Enregistre un manga dans la base de données (en attente de validation)
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'title' => [
                'required',
                'string',
                'min:3',
                'regex:/^[\pL\s\d\.\,\-\'"]+$/u',
                'max:100',
            ],
            'description' => [
                'required',
                'string',
                'regex:/^[\w\s\.,!?\'"()\-\n]{10,500}$/',
                'max:500',
            ],
            'genre' => 'required|string|in:' . implode(',', Manga::GENRES),
            'author' => [
                'required',
                'string',
                'regex:/^[a-zA-Z\s\-]{3,50}$/',
                'max:50',
            ],
        ], [
            'title.regex' => 'Le titre doit comporter entre 3 et 100 caractères alphanumériques.',
            'description.regex' => 'La description doit comporter entre 10 et 500 caractères valides.',
            'author.regex' => 'Le nom de l\'auteur doit comporter entre 3 et 50 lettres.',
        ]);

        // Création du manga en attente de validation
        Manga::create([
            'title' => $request->title,
            'description' => $request->description,
            'genre' => $request->genre,
            'author' => $request->author,
            'user_id' => Auth::id(), // Associe le manga à l'utilisateur connecté
            'is_validated' => false, // Défini comme non validé
        ]);

        return redirect()->route('mangas.index')->with('success', 'Manga ajouté en attente de validation.');
    }

    // Affiche les détails d'un manga spécifique
    public function show(Manga $manga)
    {
        if (!$manga->is_validated && !Auth::user()->is_admin) {
            abort(403, 'Accès non autorisé.');
        }

        return view('mangas.show', compact('manga'));
    }

    // Affiche le formulaire pour modifier un manga
    public function edit(Manga $manga)
    {
        // Vérifie si l'utilisateur est autorisé à modifier le manga
        if ($manga->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return redirect()->route('mangas.index')->with('error', 'Vous n\'avez pas la permission.');
        }

        return view('mangas.edit', compact('manga'));
    }

    // Met à jour un manga dans la base de données
    public function update(Request $request, Manga $manga)
    {
        // Vérifie si l'utilisateur est autorisé
        if ($manga->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return redirect()->route('mangas.index')->with('error', 'Vous n\'avez pas la permission.');
        }

        // Validation des données
        $request->validate([
            'title' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[\pL\s\d\.\,\-\'"]+$/u',
            ],
            'description' => [
                'required',
                'string',
                'min:10',
                'max:1000',
            ],
            'genre' => [
                'required',
                'string',
                'in:' . implode(',', Manga::GENRES),
            ],
            'author' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[\pL\s\-]+$/u',
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:4096', // Image optionnelle
        ]);

        // Met à jour l'image si elle est fournie
        if ($request->hasFile('image')) {
            // Supprime l'ancienne image si elle existe
            if ($manga->image_path) {
                Storage::disk('public')->delete($manga->image_path);
            }

            // Stocke la nouvelle image
            $imagePath = $request->file('image')->store('manga_images', 'public');
            $manga->image_path = $imagePath;
        }

        // Mise à jour des autres données
        $manga->update($request->only('title', 'description', 'genre', 'author'));

        return redirect()->route('mangas.index')->with('success', 'Manga mis à jour avec succès !');
    }

    // Supprime un manga de la base de données
    public function destroy(Manga $manga)
    {
        // Vérifie si l'utilisateur est autorisé
        if ($manga->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return redirect()->route('mangas.index')->with('error', 'Vous n\'avez pas la permission.');
        }

        // Supprime l'image associée si elle existe
        if ($manga->image_path) {
            Storage::disk('public')->delete($manga->image_path);
        }

        $manga->delete();

        return redirect()->route('mangas.index')->with('success', 'Manga supprimé avec succès !');
    }
}
