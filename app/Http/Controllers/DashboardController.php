<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\Comment;
use App\Models\Rating;

/**
 * Class DashboardController
 * 
 * Gère l'affichage du tableau de bord utilisateur,
 * incluant les mangas, commentaires et notes associés.
 */
class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord de l'utilisateur connecté.
     * 
     * Récupère les mangas ajoutés, les commentaires postés, 
     * et les notes attribuées par l'utilisateur connecté.
     *
     * @return \Illuminate\Contracts\View\View La vue du tableau de bord utilisateur.
     */
    public function index()
    {
        $user = auth()->user();

        // Récupérer les mangas ajoutés par l'utilisateur
        $mangas = Manga::where('user_id', $user->id)->paginate(5);

        // Récupérer les commentaires de l'utilisateur
        $comments = Comment::where('user_id', $user->id)->with('manga')->paginate(5);

        // Récupérer les notes de l'utilisateur
        $ratings = Rating::where('user_id', $user->id)->with('manga')->paginate(5);

        return view('dashboard', compact('mangas', 'comments', 'ratings'));
    }
}
