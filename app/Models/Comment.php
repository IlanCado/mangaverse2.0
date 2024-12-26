<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 *
 * Modèle représentant un commentaire dans l'application.
 *
 * @package App\Models
 *
 * @property int $id
 * @property string $content
 * @property int $user_id
 * @property int $manga_id
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Manga $manga
 * @property-read \App\Models\Comment|null $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $replies
 */
class Comment extends Model
{
    use HasFactory;

    /**
     * Les attributs pouvant être remplis via un formulaire ou une requête.
     *
     * @var array
     */
    protected $fillable = [
        'content',
        'user_id',
        'manga_id',
        'parent_id',
    ];

    /**
     * Relation : Un commentaire peut avoir un parent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Relation : Un commentaire peut avoir plusieurs réponses.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * Relation : Un commentaire appartient à un utilisateur.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : Un commentaire appartient à un manga.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manga()
    {
        return $this->belongsTo(Manga::class);
    }
}
