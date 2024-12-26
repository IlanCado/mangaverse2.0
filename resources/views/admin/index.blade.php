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

    <!-- Gestion des mangas en attente -->
    <div class="mt-5">
        <h2>Mangas en attente</h2>
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
                @foreach($mangasPending as $manga)
                    <tr>
                        <td>{{ $manga->id }}</td>
                        <td>{{ $manga->title }}</td>
                        <td>{{ $manga->author }}</td>
                        <td>{{ $manga->genre }}</td>
                        <td>{{ $manga->user->name ?? 'Utilisateur supprimé' }}</td>
                        <td>
                            <!-- Formulaire de validation -->
                            <form action="{{ route('admin.mangas.validate', $manga) }}" method="POST" enctype="multipart/form-data" style="display:inline;">
                                @csrf
                                @method('POST')
                                <div class="mb-2">
                                    <label for="image">Ajouter une image :</label>
                                    <input type="file" name="image" class="form-control" accept="image/*">
                                </div>
                                <button class="btn btn-primary">Valider</button>
                            </form>

                            <!-- Supprimer -->
                            <form action="{{ route('admin.mangas.destroy', $manga) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</button>
                            </form>

                            <!-- Voir la description -->
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#descriptionModal{{ $manga->id }}">
                                Voir la description
                            </button>

                            <!-- Modale de description -->
                            <div class="modal fade" id="descriptionModal{{ $manga->id }}" tabindex="-1" aria-labelledby="descriptionModalLabel{{ $manga->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="descriptionModalLabel{{ $manga->id }}">Description du Manga : {{ $manga->title }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{ $manga->description }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Gestion des mangas validés -->
    <div class="mt-5">
        <h2>Mangas validés</h2>
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
                @foreach($mangasValidated as $manga)
                    <tr>
                        <td>{{ $manga->id }}</td>
                        <td>{{ $manga->title }}</td>
                        <td>{{ $manga->author }}</td>
                        <td>{{ $manga->genre }}</td>
                        <td>{{ $manga->user->name ?? 'Utilisateur supprimé' }}</td>
                        <td>
                            <!-- Modifier -->
                            <a href="{{ route('admin.mangas.edit', $manga) }}" class="btn btn-warning">Modifier</a>

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

<!-- Ajout des scripts nécessaires -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
