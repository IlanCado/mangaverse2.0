@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier le manga : {{ $manga->title }}</h1>

    <!-- Affichage des erreurs globaux -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mangas.update', $manga) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" 
                   name="title" 
                   id="title" 
                   class="form-control @error('title') is-invalid @enderror" 
                   value="{{ old('title', $manga->title) }}" 
                   required>
            @error('title')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" 
                      id="description" 
                      rows="5" 
                      class="form-control @error('description') is-invalid @enderror" 
                      required>{{ old('description', $manga->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="genre" class="form-label">Genre</label>
            <select name="genre" 
                    id="genre" 
                    class="form-select @error('genre') is-invalid @enderror" 
                    required>
                <option value="" disabled>Choisissez un genre</option>
                @foreach(\App\Models\Manga::GENRES as $genre)
                    <option value="{{ $genre }}" {{ old('genre', $manga->genre) === $genre ? 'selected' : '' }}>
                        {{ $genre }}
                    </option>
                @endforeach
            </select>
            @error('genre')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="author" class="form-label">Auteur</label>
            <input type="text" 
                   name="author" 
                   id="author" 
                   class="form-control @error('author') is-invalid @enderror" 
                   value="{{ old('author', $manga->author) }}" 
                   required>
            @error('author')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection
