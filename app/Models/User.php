<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * Modèle représentant un utilisateur de l'application.
 *
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property bool $is_admin
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Manga[] $mangas
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Rating[] $ratings
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Les attributs autorisés pour l'assignation en masse.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin', // Colonne pour savoir si l'utilisateur est admin
    ];

    /**
     * Les attributs cachés pour la sérialisation.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs avec casting.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean', // Casting de la colonne admin en booléen
    ];

    /**
     * Vérifie si l'utilisateur est administrateur.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * Relation : Un utilisateur peut avoir plusieurs mangas.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function mangas()
    {
        return $this->hasMany(Manga::class);
    }

    /**
     * Relation : Un utilisateur peut avoir plusieurs commentaires.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relation : Un utilisateur peut avoir plusieurs notes (ratings).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
