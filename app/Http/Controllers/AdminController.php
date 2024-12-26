<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Manga;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Affiche la page d'administration.
     */
    public function index()
    {
        $users = User::all();
        $mangas = Manga::all();

        return view('admin.index', compact('users', 'mangas'));
    }

    /**
     * Supprime un manga.
     */
    public function destroyManga(Manga $manga)
    {
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
}
