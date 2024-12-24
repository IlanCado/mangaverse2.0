<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'manga_id',
        'parent_id',
    ];

    /**
     * Relation : Un commentaire peut avoir un parent.
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Relation : Un commentaire peut avoir plusieurs réponses.
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * Relation : Un commentaire appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : Un commentaire appartient à un manga.
     */
    public function manga()
    {
        return $this->belongsTo(Manga::class);
    }
}
