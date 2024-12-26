<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware pour restreindre l'accès aux administrateurs.
 */
class AdminMiddleware
{
    /**
     * Gère une requête entrante.
     *
     * Vérifie si l'utilisateur est authentifié et possède le rôle d'administrateur.
     * Sinon, une erreur 403 est retournée.
     *
     * @param  \Illuminate\Http\Request  $request  La requête HTTP entrante.
     * @param  \Closure  $next  La fonction suivante dans la chaîne de middleware.
     * @return mixed  La réponse HTTP.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException Si l'utilisateur n'est pas admin.
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifie si l'utilisateur est authentifié et admin
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        return $next($request);
    }
}
