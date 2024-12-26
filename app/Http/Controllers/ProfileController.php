<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * Class ProfileController
 *
 * Gère les opérations liées au profil de l'utilisateur, telles que l'affichage, la mise à jour et la suppression.
 */
class ProfileController extends Controller
{
    /**
     * Affiche le formulaire du profil utilisateur.
     *
     * @param Request $request La requête HTTP contenant les informations utilisateur.
     * @return View La vue affichant le formulaire d'édition du profil.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Met à jour les informations du profil de l'utilisateur.
     *
     * @param ProfileUpdateRequest $request La requête validée contenant les nouvelles données du profil.
     * @return RedirectResponse Redirection vers le formulaire du profil avec un message de succès.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        // Réinitialise la vérification de l'email si celui-ci a été modifié
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Supprime le compte de l'utilisateur.
     *
     * @param Request $request La requête HTTP contenant les informations utilisateur.
     * @return RedirectResponse Redirection vers la page d'accueil après la suppression.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Valide le mot de passe avant la suppression
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Déconnexion de l'utilisateur
        Auth::logout();

        // Suppression du compte
        $user->delete();

        // Invalidation de la session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
