@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Liste des mangas</h1>

    <!-- Formulaire de filtres et de tri -->
    <form action="{{ route('mangas.index') }}" method="GET" class="mb-4">
        <div class="row">
            <!-- Recherche par mots-clés -->
            <div class="col-md-3 mb-3">
                <input type="text" name="search" class="form-control" placeholder="Rechercher par titre, auteur ou description..." value="{{ request('search') }}">
            </div>

            <!-- Filtrer par genre -->
            <div class="col-md-3 mb-3">
                <select name="genre" class="form-control">
                    <option value="">Tous les genres</option>
                    @foreach(\App\Models\Manga::GENRES as $genre)
                        <option value="{{ $genre }}" {{ request('genre') === $genre ? 'selected' : '' }}>{{ $genre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Trier par -->
            <div class="col-md-3 mb-3">
                <select name="sort" class="form-control">
                    <option value="">Trier par</option>
                    <option value="best_rating" {{ request('sort') === 'best_rating' ? 'selected' : '' }}>Meilleure note</option>
                    <option value="worst_rating" {{ request('sort') === 'worst_rating' ? 'selected' : '' }}>Moins bonne note</option>
                    <option value="most_votes" {{ request('sort') === 'most_votes' ? 'selected' : '' }}>Plus grand nombre de votes</option>
                    <option value="least_votes" {{ request('sort') === 'least_votes' ? 'selected' : '' }}>Plus petit nombre de votes</option>
                    <option value="most_comments" {{ request('sort') === 'most_comments' ? 'selected' : '' }}>Plus grand nombre de commentaires</option>
                    <option value="least_comments" {{ request('sort') === 'least_comments' ? 'selected' : '' }}>Plus petit nombre de commentaires</option>
                </select>
            </div>

            <!-- Bouton de soumission -->
            <div class="col-md-3 mb-3">
                <button type="submit" class="btn btn-primary w-100">Appliquer les filtres</button>
            </div>
        </div>
    </form>

    <!-- Affichage des mangas -->
    @if($mangas->isEmpty())
        <p>Aucun manga disponible pour le moment.</p>
    @else
        <div class="row">
            @foreach($mangas as $manga)
                <div class="col-md-12 mb-4">
                    <div class="d-flex bg-white shadow-sm p-3 rounded">
                        <!-- Image -->
                        <div class="flex-shrink-0">
                            @if($manga->image_path)
                                <img src="{{ asset('storage/' . $manga->image_path) }}" alt="{{ $manga->title }}" class="img-fluid rounded" style="max-width: 150px; max-height: 200px;">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}" alt="Image indisponible" class="img-fluid rounded" style="max-width: 150px; max-height: 200px;">
                            @endif
                        </div>

                        <!-- Contenu principal -->
                        <div class="ms-3 flex-grow-1 position-relative">
                            <a href="{{ route('mangas.show', $manga) }}" class="fw-bold text-primary">{{ $manga->title }}</a>
                            <h6 class="text-muted">Auteur : {{ $manga->author }}</h6>
                            <p class="text-muted mb-1"><strong>Genre :</strong> {{ $manga->genre }}</p>

                            <!-- Étoiles interactives -->
                            <div class="rating-container position-absolute" style="top: 0; right: 20px;">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="fa fa-star {{ $i <= round($manga->averageRating()) ? 'checked' : '' }}" style="font-size: 24px;"></span>
                                @endfor
                            </div>

                            <p class="fw-bold mt-3">Note Moyenne :
                                <span class="text-warning">
                                    @if($manga->averageRating())
                                        {{ number_format($manga->averageRating(), 1) }} / 5
                                    @else
                                        Pas encore noté
                                    @endif
                                </span>
                            </p>

                            <p class="mb-2"><strong>Description :</strong> {{ \Illuminate\Support\Str::limit($manga->description, 150, '...') }}</p>
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

<!-- Styles pour les étoiles -->
<style>
    .fa-star {
        font-size: 1.5rem;
        color: lightgray;
        margin-right: 5px;
    }
    .fa-star.checked {
        color: gold;
    }
    .rating-container {
        display: flex;
        align-items: center;
    }
</style>
@endsection
