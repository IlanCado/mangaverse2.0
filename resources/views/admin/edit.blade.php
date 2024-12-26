@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Modifier le Manga</h1>

    <!-- Messages de validation -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.mangas.update', $manga) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Titre -->
        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $manga->title) }}" required>
        </div>

        <!-- Auteur -->
        <div class="mb-3">
            <label for="author" class="form-label">Auteur</label>
            <input type="text" name="author" id="author" class="form-control" value="{{ old('author', $manga->author) }}" required>
        </div>

        <!-- Genre -->
        <div class="mb-3">
            <label for="genre" class="form-label">Genre</label>
            <select name="genre" id="genre" class="form-control" required>
                @foreach(\App\Models\Manga::GENRES as $genre)
                    <option value="{{ $genre }}" {{ old('genre', $manga->genre) === $genre ? 'selected' : '' }}>
                        {{ $genre }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="5" required>{{ old('description', $manga->description) }}</textarea>
        </div>

        <!-- Image actuelle -->
        @if($manga->image_path)
            <div class="mb-3">
                <label class="form-label">Image actuelle</label>
                <div>
                    <img src="{{ asset('storage/' . $manga->image_path) }}" alt="Image du manga" class="img-fluid" style="max-height: 200px;">
                </div>
            </div>
        @endif

        <!-- Nouvelle image -->
        <div class="mb-3">
            <label for="image" class="form-label">Nouvelle Image (optionnelle)</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
        </div>

        <!-- Bouton de soumission -->
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="{{ route('admin.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection
