@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $manga->title }}</h1>
    <p><strong>Auteur :</strong> {{ $manga->author }}</p>
    <p><strong>Genre :</strong> {{ $manga->genre }}</p>
    <p>{{ $manga->description }}</p>

    <hr>
    <h2>Notez ce manga</h2>

@auth
    <form action="{{ route('ratings.store', $manga) }}" method="POST" id="rating-form">
        @csrf
        <div class="rating-container">
            <div class="rating">
                @for ($i = 1; $i <= 5; $i++)
                    <input 
                        type="radio" 
                        id="star{{ $i }}" 
                        name="rating" 
                        value="{{ $i }}" 
                        {{ $manga->ratings->where('user_id', auth()->id())->first()?->rating == $i ? 'checked' : '' }} 
                        onchange="document.getElementById('rating-form').submit();">
                    <label for="star{{ $i }}">&#9733;</label>
                @endfor
            </div>
        </div>
    </form>

   
@endauth


    <!-- Affichage de la moyenne des notes -->
    @if($manga->ratings->count() > 0)
        <p><strong>Moyenne des notes :</strong> {{ number_format($manga->averageRating(), 1) }} / 5 ({{ $manga->ratings->count() }} votes)</p>
        <p><strong>Nombre de votes :</strong> {{ $manga->ratings->count() }}</p>
    @else
        <p>Aucune note pour ce manga pour l'instant.</p>
    @endif

    <hr>
    <h2>Commentaires</h2>

    @if($manga->comments->where('parent_id', null)->isEmpty())
        <p>Aucun commentaire pour ce manga.</p>
    @else
        @foreach($manga->comments->where('parent_id', null) as $comment)
            <div>
                <p><strong>{{ $comment->user->name }}</strong> : {{ $comment->content }}</p>

                <!-- Actions : Modifier ou supprimer le commentaire -->
                @if(auth()->id() === $comment->user_id || auth()->user()->is_admin)
                    <a href="{{ route('comments.edit', $comment) }}" class="btn btn-warning btn-sm">Modifier</a>
                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                    </form>
                @endif

                <!-- Formulaire pour répondre au commentaire -->
                @auth
                    <form action="{{ route('comments.reply', $comment) }}" method="POST" style="margin-top: 10px;">
                        @csrf
                        <div class="mb-3">
                            <label for="reply-content-{{ $comment->id }}" class="form-label">Répondre :</label>
                            <textarea name="content" id="reply-content-{{ $comment->id }}" rows="2" class="form-control" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-secondary btn-sm">Répondre</button>
                    </form>
                @endauth

                <!-- Affichage des réponses -->
                @if($comment->replies->isNotEmpty())
                    <div style="margin-left: 20px; border-left: 1px solid #ccc; padding-left: 10px; margin-top: 10px;">
                        @foreach($comment->replies as $reply)
                            <p><strong>{{ $reply->user->name }}</strong> : {{ $reply->content }}</p>

                            <!-- Actions pour les réponses -->
                            @if(auth()->id() === $reply->user_id || auth()->user()->is_admin)
                                <a href="{{ route('comments.edit', $reply) }}" class="btn btn-warning btn-sm">Modifier</a>
                                <form action="{{ route('comments.destroy', $reply) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
            <hr>
        @endforeach
    @endif

    <!-- Formulaire pour ajouter un commentaire principal -->
    @auth
        <form action="{{ route('comments.store', $manga) }}" method="POST" style="margin-top: 20px;">
            @csrf
            <div class="mb-3">
                <label for="content" class="form-label">Ajouter un commentaire :</label>
                <textarea name="content" id="content" rows="3" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Commenter</button>
        </form>
    @endauth
</div>
@endsection
