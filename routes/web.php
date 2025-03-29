<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/**
 * Enregistrement des routes web de l'application.
 *
 * Ce fichier définit toutes les routes de l'application, en distinguant :
 * - Les routes accessibles à tous.
 * - Les routes nécessitant une connexion.
 * - Les routes réservées aux administrateurs.
 */

// ==========================================
// Routes accessibles à tous (visiteurs et connectés)
// ==========================================

/**
 * Page d'accueil avec la liste des mangas validés.
 */
Route::get('/', [MangaController::class, 'index'])->name('home');

/**
 * Liste des mangas avec filtres et recherche (accessible à tous).
 */
Route::get('mangas', [MangaController::class, 'index'])->name('mangas.index');

/**
 * Affichage des détails d'un manga.
 */
Route::get('mangas/{manga}', [MangaController::class, 'show'])->name('mangas.show');

// ==========================================
// Routes nécessitant une connexion (authentification obligatoire)
// ==========================================
Route::middleware(['auth'])->group(function () {

    /**
     * Affichage du formulaire de création d'un manga.
     */
    Route::get('mangas/create', [MangaController::class, 'create'])->name('mangas.create');

    /**
     * Gestion des mangas (ajout, modification, suppression).
     */
    Route::resource('mangas', MangaController::class)->only([
        'store', 'edit', 'update', 'destroy',
    ]);

    /**
     * Tableau de bord utilisateur.
     */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /**
     * Gestion des commentaires (ajout, modification, suppression, réponses).
     */
    Route::post('/mangas/{manga}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/reply', [CommentController::class, 'replyStore'])->name('comments.reply');

    /**
     * Enregistrement des notes (ratings).
     */
    Route::post('/mangas/{manga}/rate', [RatingController::class, 'store'])->name('ratings.store');

    /**
     * Gestion du profil utilisateur.
     */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// Authentification Laravel Breeze
// ==========================================
require __DIR__.'/auth.php';

// ==========================================
// Routes d'administration (réservées aux administrateurs)
// ==========================================
Route::middleware(['auth', 'admin'])->group(function () {

    /**
     * Accueil de l'administration.
     */
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    /**
     * Gestion des mangas dans l'interface admin.
     */
    Route::delete('/admin/mangas/{manga}', [AdminController::class, 'destroyManga'])->name('admin.mangas.destroy');
    Route::post('/admin/mangas/{manga}/validate', [AdminController::class, 'validateManga'])->name('admin.mangas.validate');
    Route::get('/admin/mangas/{manga}/edit', [AdminController::class, 'editManga'])->name('admin.mangas.edit');
    Route::put('/admin/mangas/{manga}', [AdminController::class, 'updateManga'])->name('admin.mangas.update');

    /**
     * Gestion des utilisateurs (suppression uniquement pour l'instant).
     */
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
});