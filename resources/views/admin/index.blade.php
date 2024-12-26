@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">Page d'administration</h1>

    <!-- Messages de succès -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Gestion des utilisateurs -->
    <div class="mt-5">
        <h2>Utilisateurs</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Gestion des mangas -->
    <div class="mt-5">
        <h2>Mangas</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Genre</th>
                    <th>Créé par</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mangas as $manga)
                    <tr>
                        <td>{{ $manga->id }}</td>
                        <td>{{ $manga->title }}</td>
                        <td>{{ $manga->author }}</td>
                        <td>{{ $manga->genre }}</td>
                        <td>{{ $manga->user->name ?? 'Utilisateur supprimé' }}</td>
                        <td>
                            <!-- Modifier -->
                            <a href="{{ route('mangas.edit', $manga) }}" class="btn btn-warning">Modifier</a>
                            
                            <!-- Supprimer -->
                            <form action="{{ route('admin.mangas.destroy', $manga) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
