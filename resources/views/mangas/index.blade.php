@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Liste des mangas</h1>

    <!-- Bouton d'ajout de manga : Visible uniquement pour les utilisateurs connectés -->
    @auth
        <a href="{{ route('mangas.create') }}" class="btn btn-primary mb-3">Ajouter un manga</a>
    @endauth

    <!-- Vérification de la disponibilité des mangas -->
    @if($mangas->isEmpty())
        <p>Aucun manga disponible pour le moment.</p>
    @else
        <div class="row">
            @foreach($mangas as $manga)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-primary">{{ $manga->title }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">Auteur : {{ $manga->author }}</h6>
                            <p class="card-text"><strong>Genre :</strong> {{ $manga->genre }}</p>
                            <p class="card-text"><strong>Description :</strong> {{ \Illuminate\Support\Str::limit($manga->description, 150, '...') }}</p>
                            <a href="{{ route('mangas.show', $manga) }}" class="btn btn-primary">Voir les détails</a>

                            <!-- Actions pour modifier ou supprimer : Visible uniquement pour le propriétaire ou l'admin -->
                            @auth
                                @if(auth()->id() === $manga->user_id || auth()->user()->is_admin)
                                    <a href="{{ route('mangas.edit', $manga) }}" class="btn btn-warning">Modifier</a>
                                    <form action="{{ route('mangas.destroy', $manga) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Supprimer</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination des mangas -->
        <div class="d-flex justify-content-center mt-4">
            {{ $mangas->links() }}
        </div>
    @endif
</div>
@endsection
