@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mt-5">
        <!-- Image et détails du manga -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <img src="{{ $manga->image_path ? asset('storage/' . $manga->image_path) : asset('images/placeholder.png') }}" 
                     alt="Image de {{ $manga->title }}" class="card-img-top img-fluid rounded">
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow-sm p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <h1 class="card-title text-primary me-3">{{ $manga->title }}</h1>
                    @auth
                        <div id="interactive-rating" class="interactive-rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <span 
                                    class="star {{ $manga->ratings->where('user_id', auth()->id())->first()?->rating >= $i ? 'selected' : '' }}" 
                                    data-value="{{ $i }}">
                                    &#9733;
                                </span>
                            @endfor
                        </div>
                        <form action="{{ route('ratings.store', $manga) }}" method="POST" id="rating-form" class="d-none">
                            @csrf
                            <input type="hidden" name="rating" id="rating-input" value="{{ $manga->ratings->where('user_id', auth()->id())->first()?->rating }}">
                        </form>
                    @else
                        <p><a href="{{ route('login') }}" class="text-primary">Connectez-vous</a> pour noter ce manga.</p>
                    @endauth
                </div>
                <h4 class="card-subtitle mb-3 text-muted">Par {{ $manga->author }}</h4>
                <p><strong>Genre :</strong> {{ $manga->genre }}</p>
                <p><strong>Description :</strong> {{ $manga->description }}</p>
                <p>
                    <strong>Note Moyenne :</strong> 
                    <span class="badge bg-warning text-dark">
                        {{ number_format($manga->averageRating(), 1) ?? 'Non noté' }}
                    </span>
                </p>
                <p><strong>Nombre de votes :</strong> {{ $manga->ratings->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Section des commentaires -->
    <div class="row mt-5">
        <div class="col-md-12">
            <h2>Commentaires</h2>

            <!-- Liste des commentaires -->
            @if($manga->comments->where('parent_id', null)->isEmpty())
                <p>Aucun commentaire pour ce manga.</p>
            @else
                <ul class="list-group">
                    @foreach($manga->comments->where('parent_id', null) as $comment)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $comment->user->name }}</strong>
                                    <span class="text-muted">· {{ $comment->created_at->diffForHumans() }}</span>
                                    <p>{{ $comment->content }}</p>
                                </div>
                                <div>
                                    @if(auth()->id() === $comment->user_id || auth()->user()->is_admin)
                                        <a href="{{ route('comments.edit', $comment) }}" class="btn btn-warning btn-sm">Modifier</a>
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <!-- Affichage des réponses -->
                            @if($comment->replies->isNotEmpty())
                                <ul class="list-group mt-3">
                                    @foreach($comment->replies as $reply)
                                        <li class="list-group-item">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <strong>{{ $reply->user->name }}</strong>
                                                    <span class="text-muted">· {{ $reply->created_at->diffForHumans() }}</span>
                                                    <p>{{ $reply->content }}</p>
                                                </div>
                                                <div>
                                                    @if(auth()->id() === $reply->user_id || auth()->user()->is_admin)
                                                        <a href="{{ route('comments.edit', $reply) }}" class="btn btn-warning btn-sm">Modifier</a>
                                                        <form action="{{ route('comments.destroy', $reply) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <!-- Formulaire de réponse -->
                            @auth
                                <form action="{{ route('comments.reply', $comment) }}" method="POST" class="mt-3">
                                    @csrf
                                    <div class="mb-3">
                                        <textarea name="content" rows="2" class="form-control" placeholder="Répondre à ce commentaire..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-secondary btn-sm">Répondre</button>
                                </form>
                            @endauth
                        </li>
                    @endforeach
                </ul>
            @endif

            <!-- Formulaire pour ajouter un commentaire principal -->
            @auth
                <form action="{{ route('comments.store', $manga) }}" method="POST" class="mt-4">
                    @csrf
                    <div class="mb-3">
                        <textarea name="content" rows="3" class="form-control" placeholder="Ajouter un commentaire..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Commenter</button>
                </form>
            @endauth
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const stars = document.querySelectorAll('#interactive-rating .star');
        const ratingForm = document.getElementById('rating-form');
        const ratingInput = document.getElementById('rating-input');

        stars.forEach(star => {
            star.addEventListener('mouseover', () => {
                stars.forEach(s => s.classList.remove('hovered'));
                for (let i = 0; i < star.dataset.value; i++) {
                    stars[i].classList.add('hovered');
                }
            });

            star.addEventListener('mouseleave', () => {
                stars.forEach(s => s.classList.remove('hovered'));
            });

            star.addEventListener('click', () => {
                stars.forEach(s => s.classList.remove('selected'));
                for (let i = 0; i < star.dataset.value; i++) {
                    stars[i].classList.add('selected');
                }
                ratingInput.value = star.dataset.value;
                ratingForm.submit();
            });
        });
    });
</script>

<style>
    .interactive-rating .star {
        font-size: 2.5rem;
        color: #ccc;
        cursor: pointer;
        transition: color 0.2s ease-in-out;
    }

    .interactive-rating .star.hovered,
    .interactive-rating .star.selected {
        color: gold;
    }
</style>
@endsection
