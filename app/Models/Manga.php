<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Manga
 *
 * Modèle représentant un manga dans l'application.
 *
 * @package App\Models
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $genre
 * @property string $author
 * @property int $user_id
 * @property bool $is_validated
 * @property string|null $image_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Rating[] $ratings
 */
class Manga extends Model
{
    use HasFactory;

    /**
     * Les genres disponibles pour les mangas.
     *
     * @var array
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
        'is_validated', // Indique si le manga est validé par un administrateur
        'image_path',   // Chemin de l'image associée
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
     * Calcule la moyenne des notes pour ce manga.
     *
     * @return float|null La moyenne des notes ou null si aucune note n'existe.
     */
    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }
}
