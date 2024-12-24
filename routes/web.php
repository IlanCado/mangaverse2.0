<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Page d'accueil : liste des mangas
Route::get('/', [MangaController::class, 'index'])->name('home');

// Routes nécessitant une connexion
Route::middleware(['auth'])->group(function () {
    // Gestion des mangas : ajout, modification, suppression
    Route::resource('mangas', MangaController::class)->only(['index','show','create', 'store', 'edit', 'update', 'destroy']);

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
