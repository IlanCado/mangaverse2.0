<?php

namespace App\Http\Controllers;

use App\Models\Manga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Class MangaController
 *
 * Gère les fonctionnalités liées aux mangas : affichage, création, modification, suppression et validation.
 */
class MangaController extends Controller
{
    /**
     * Affiche la liste des mangas validés avec recherche, filtres et tri.
     *
     * @param Request $request La requête HTTP contenant les paramètres de recherche, filtres et tri.
     * @return \Illuminate\Contracts\View\View La vue affichant la liste des mangas.
     */
    public function index(Request $request)
    {
        $query = Manga::where('is_validated', true);

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

        // Tri des résultats
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'best_rating':
                    $query->withAvg('ratings', 'rating')->orderByDesc('ratings_avg_rating');
                    break;
                case 'worst_rating':
                    $query->withAvg('ratings', 'rating')->orderBy('ratings_avg_rating');
                    break;
                case 'most_votes':
                    $query->withCount('ratings')->orderByDesc('ratings_count');
                    break;
                case 'least_votes':
                    $query->withCount('ratings')->orderBy('ratings_count');
                    break;
                case 'most_comments':
                    $query->withCount('comments')->orderByDesc('comments_count');
                    break;
                case 'least_comments':
                    $query->withCount('comments')->orderBy('comments_count');
                    break;
            }
        }

        $mangas = $query->paginate(10);

        return view('mangas.index', compact('mangas'));
    }

    /**
     * Affiche le formulaire pour ajouter un manga.
     *
     * @return \Illuminate\Contracts\View\View La vue du formulaire de création de manga.
     */
    public function create()
    {
        return view('mangas.create');
    }

    /**
     * Enregistre un nouveau manga dans la base de données.
     *
     * @param Request $request La requête HTTP contenant les données du manga.
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste des mangas avec un message de succès.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:3|max:100',
            'description' => 'required|string|min:10|max:500',
            'genre' => 'required|string|in:' . implode(',', Manga::GENRES),
            'author' => 'required|string|min:3|max:50',
        ]);

        Manga::create([
            'title' => $request->title,
            'description' => $request->description,
            'genre' => $request->genre,
            'author' => $request->author,
            'user_id' => Auth::id(),
            'is_validated' => false,
        ]);

        return redirect()->route('mangas.index')->with('info', 'Manga en attente de validation.');
    }

    /**
     * Affiche les détails d'un manga spécifique.
     *
     * @param Manga $manga Le manga à afficher.
     * @return \Illuminate\Contracts\View\View La vue affichant les détails du manga.
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Si l'accès est non autorisé.
     */
    public function show(Manga $manga)
    {
        if (!$manga->is_validated && !Auth::user()->is_admin) {
            abort(403, 'Accès non autorisé.');
        }

        return view('mangas.show', compact('manga'));
    }

    /**
     * Affiche le formulaire pour modifier un manga existant.
     *
     * @param Manga $manga Le manga à modifier.
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse La vue du formulaire de modification ou une redirection avec un message d'erreur.
     */
    public function edit(Manga $manga)
    {
        if ($manga->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return redirect()->route('mangas.index')->with('error', 'Vous n\'avez pas la permission.');
        }

        return view('mangas.edit', compact('manga'));
    }

    /**
     * Met à jour un manga existant dans la base de données.
     *
     * @param Request $request La requête HTTP contenant les nouvelles données du manga.
     * @param Manga $manga Le manga à mettre à jour.
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste des mangas avec un message de succès.
     */
    public function update(Request $request, Manga $manga)
    {
        if ($manga->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return redirect()->route('mangas.index')->with('error', 'Vous n\'avez pas la permission.');
        }

        $request->validate([
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10|max:1000',
            'genre' => 'required|string|in:' . implode(',', Manga::GENRES),
            'author' => 'required|string|min:3|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        if ($request->hasFile('image')) {
            if ($manga->image_path) {
                Storage::disk('public')->delete($manga->image_path);
            }

            $imagePath = $request->file('image')->store('manga_images', 'public');
            $manga->image_path = $imagePath;
        }

        $manga->update($request->only('title', 'description', 'genre', 'author'));

        return redirect()->route('mangas.index')->with('success', 'Manga mis à jour avec succès !');
    }

    /**
     * Supprime un manga de la base de données.
     *
     * @param Manga $manga Le manga à supprimer.
     * @return \Illuminate\Http\RedirectResponse Redirection vers la liste des mangas avec un message de succès.
     */
    public function destroy(Manga $manga)
    {
        if ($manga->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return redirect()->route('mangas.index')->with('error', 'Vous n\'avez pas la permission.');
        }

        if ($manga->image_path) {
            Storage::disk('public')->delete($manga->image_path);
        }

        $manga->delete();

        return redirect()->route('mangas.index')->with('success', 'Manga supprimé avec succès !');
    }
}
