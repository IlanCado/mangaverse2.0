<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manga extends Model
{
    use HasFactory;

    /**
     * Les genres disponibles pour les mangas.
     */
    public const GENRES = [
        'Action',
        'Aventure',
        'Comédie',
        'Drame',
        'Fantaisie',
        'Horreur',
        'Romance',
        'Science-Fiction',
        'Slice of Life',
        'Sports',
        'Thriller',
        'Mystère',
        'Mecha',
    ];

    /**
     * Les attributs autorisés pour l'insertion en masse.
     *
     * @var array
     */
    protected $fillable = [
        'title',        // Titre du manga
        'description',  // Description du manga
        'genre',        // Genre du manga
        'author',       // Auteur du manga
        'user_id',      // ID de l'utilisateur ayant créé le manga
        'is_validated', //Validé par admin
        'image_path',   // image rajouté par admin
    ];

    /**
     * Relation : Un manga appartient à un utilisateur (créateur).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : Un manga peut avoir plusieurs commentaires.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relation : Un manga peut avoir plusieurs notes (ratings).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Calcule la moyenne des notes.
     *
     * @return float|null
     */
    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }
}
