@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier le manga : {{ $manga->title }}</h1>

    <form action="{{ route('mangas.update', $manga) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $manga->title }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="5" class="form-control" required>{{ $manga->description }}</textarea>
        </div>

        <div class="mb-3">
            <label for="genre" class="form-label">Genre</label>
            <input type="text" name="genre" id="genre" class="form-control" value="{{ $manga->genre }}" required>
        </div>

        <div class="mb-3">
            <label for="author" class="form-label">Auteur</label>
            <input type="text" name="author" id="author" class="form-control" value="{{ $manga->author }}" required>
        </div>

        <button type="submit" class="btn btn-warning">Mettre à jour</button>
    </form>
</div>
@endsection
