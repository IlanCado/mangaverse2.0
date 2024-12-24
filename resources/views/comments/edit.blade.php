@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Modifier le commentaire</h1>

    <form action="{{ route('comments.update', $comment->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="content" class="form-label">Contenu du commentaire</label>
            <textarea name="content" id="content" rows="5" class="form-control" required>{{ $comment->content }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection
