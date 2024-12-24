@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tableau de Bord</h1>

    <div class="row">
        <!-- Informations utilisateur -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Mon Profil</h4>
                </div>
                <div class="card-body">
                    <p><strong>Nom :</strong> {{ auth()->user()->name }}</p>
                    <p><strong>Email :</strong> {{ auth()->user()->email }}</p>
                    <p><strong>Date d'inscription :</strong> {{ auth()->user()->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Modifier les informations utilisateur -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Modifier mes informations</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ auth()->user()->name }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ auth()->user()->email }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe (facultatif)</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Laissez vide pour conserver le mot de passe actuel">
                        </div>

                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </form>
                </div>
            </div>

            <!-- Liste des mangas de l'utilisateur -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Mes Mangas</h4>
                </div>
                <div class="card-body">
                    @if($mangas->isEmpty())
                        <p>Vous n'avez ajouté aucun manga pour le moment.</p>
                        <a href="{{ route('mangas.create') }}" class="btn btn-success">Ajouter un manga</a>
                    @else
                        <ul class="list-group">
                            @foreach($mangas as $manga)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $manga->title }}
                                    <span>
                                        <a href="{{ route('mangas.edit', $manga) }}" class="btn btn-warning btn-sm">Modifier</a>
                                        <form action="{{ route('mangas.destroy', $manga) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                        </form>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-3">
                            {{ $mangas->links() }} <!-- Pagination -->
                        </div>
                    @endif
                </div>
            </div>

            <!-- Liste des commentaires de l'utilisateur -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Mes Commentaires</h4>
                </div>
                <div class="card-body">
                    @if($comments->isEmpty())
                        <p>Vous n'avez écrit aucun commentaire pour le moment.</p>
                    @else
                        <ul class="list-group">
                            @foreach($comments as $comment)
                                <li class="list-group-item">
                                    <p><strong>Manga :</strong> {{ $comment->manga->title }}</p>
                                    <p><strong>Commentaire :</strong> {{ $comment->content }}</p>
                                    <p><small>Posté le {{ $comment->created_at->format('d/m/Y à H:i') }}</small></p>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-3">
                            {{ $comments->links() }} <!-- Pagination -->
                        </div>
                    @endif
                </div>
            </div>

            <!-- Liste des notes données par l'utilisateur -->
            <div class="card">
                <div class="card-header">
                    <h4>Mes Notes</h4>
                </div>
                <div class="card-body">
                    @if($ratings->isEmpty())
                        <p>Vous n'avez noté aucun manga pour le moment.</p>
                    @else
                        <ul class="list-group">
                            @foreach($ratings as $rating)
                                <li class="list-group-item">
                                    <p><strong>Manga :</strong> {{ $rating->manga->title }}</p>
                                    <p><strong>Note :</strong> {{ $rating->rating }} / 5</p>
                                    <p><small>Posté le {{ $rating->created_at->format('d/m/Y à H:i') }}</small></p>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-3">
                            {{ $ratings->links() }} <!-- Pagination -->
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
