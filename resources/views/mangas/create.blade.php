@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ajouter un manga</h1>

    <form action="{{ route('mangas.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="5" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label for="genre" class="form-label">Genre</label>
            <select name="genre" id="genre" class="form-control" required>
                <option value="" disabled selected>Choisissez un genre</option>
                @foreach(\App\Models\Manga::GENRES as $genre)
                    <option value="{{ $genre }}">{{ $genre }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="author" class="form-label">Auteur</label>
            <input type="text" name="author" id="author" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>
@endsection
