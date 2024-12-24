@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Liste des mangas</h1>

    <!-- Formulaire de filtres -->
    <form action="{{ route('mangas.index') }}" method="GET" class="mb-4">
        <div class="row">
            <!-- Recherche par mots-clés -->
            <div class="col-md-4 mb-3">
                <input type="text" name="search" class="form-control" placeholder="Rechercher par titre, auteur ou description..." value="{{ request('search') }}">
            </div>

            <!-- Filtrer par genre -->
            <div class="col-md-3 mb-3">
                <select name="genre" class="form-control">
                    <option value="">Tous les genres</option>
                    @foreach(\App\Models\Manga::GENRES as $genre)
                        <option value="{{ $genre }}" {{ request('genre') === $genre ? 'selected' : '' }}>
                            {{ $genre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtrer par note moyenne -->
            <div class="col-md-3 mb-3">
                <select name="rating" class="form-control">
                    <option value="">Toutes les notes</option>
                    <option value="5" {{ request('rating') === '5' ? 'selected' : '' }}>5 étoiles</option>
                    <option value="4" {{ request('rating') === '4' ? 'selected' : '' }}>4 étoiles et plus</option>
                    <option value="3" {{ request('rating') === '3' ? 'selected' : '' }}>3 étoiles et plus</option>
                    <option value="2" {{ request('rating') === '2' ? 'selected' : '' }}>2 étoiles et plus</option>
                    <option value="1" {{ request('rating') === '1' ? 'selected' : '' }}>1 étoile et plus</option>
                </select>
            </div>

            <!-- Bouton de soumission -->
            <div class="col-md-2 mb-3">
                <button type="submit" class="btn btn-primary w-100">Filtrer</button>
            </div>
        </div>
    </form>

    <!-- Affichage des mangas -->
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
                            <p class="card-text"><strong>Note Moyenne :</strong> {{ number_format($manga->averageRating(), 1) ?? 'Non noté' }}</p>
                            <a href="{{ route('mangas.show', $manga) }}" class="btn btn-primary">Voir les détails</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $mangas->links() }}
        </div>
    @endif
</div>
@endsection
