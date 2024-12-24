<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    /**
     * Attributs autorisés pour l'assignation en masse.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',   // ID de l'utilisateur ayant noté
        'manga_id',  // ID du manga noté
        'rating',    // Note attribuée (1 à 5)
    ];

    /**
     * Relation : Une note appartient à un utilisateur.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : Une note appartient à un manga.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manga()
    {
        return $this->belongsTo(Manga::class);
    }
}
