<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/**
 * Register the application's web routes.
 *
 * These routes handle navigation and actions for authenticated and non-authenticated users,
 * including routes for the homepage, dashboard, administration panel, and CRUD operations
 * for mangas, comments, ratings, and profiles.
 */

// Page d'accueil : liste des mangas validés uniquement
Route::get('/', [MangaController::class, 'index'])->name('home');

/**
 * Routes nécessitant une connexion.
 * Les utilisateurs doivent être connectés pour accéder à ces routes.
 */
Route::middleware(['auth'])->group(function () {
    // Gestion des mangas : ajout, modification, suppression
    Route::resource('mangas', MangaController::class)->only([
        'index', 'show', 'create', 'store', 'edit', 'update', 'destroy',
    ]);

    // Tableau de bord
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gestion des commentaires
    Route::post('/mangas/{manga}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/comments/{comment}/reply', [CommentController::class, 'replyStore'])->name('comments.reply');

    // Gestion des notes
    Route::post('/mangas/{manga}/rate', [RatingController::class, 'store'])->name('ratings.store');

    // Gestion du profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Authentification
require __DIR__.'/auth.php';

/**
 * Routes d'administration.
 * Ces routes nécessitent une connexion et des privilèges d'administrateur.
 */
Route::middleware(['auth', 'admin'])->group(function () {
    // Page principale de l'administration
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    // Gestion des mangas dans l'administration
    Route::delete('/admin/mangas/{manga}', [AdminController::class, 'destroyManga'])->name('admin.mangas.destroy');
    Route::post('/admin/mangas/{manga}/validate', [AdminController::class, 'validateManga'])->name('admin.mangas.validate');
    Route::get('/admin/mangas/{manga}/edit', [AdminController::class, 'editManga'])->name('admin.mangas.edit');
    Route::put('/admin/mangas/{manga}', [AdminController::class, 'updateManga'])->name('admin.mangas.update');

    // Gestion des utilisateurs dans l'administration
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
});
