<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mangaverse')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Inline styles for background fallback */
        body {
            background-image: url('/images/back.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        .content-container {
            background-color: rgba(255, 255, 255, 0.9); /* Semi-transparent white */
            border-radius: 10px; /* Rounded corners */
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">Mangaverse</a>

            <!-- Toggler (mobile) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <!-- Lien pour explorer les mangas -->
                    <li class="nav-item mx-2">
                        <a class="nav-link text-white" href="{{ route('home') }}">Explorer les Mangas</a>
                    </li>

                    @auth
                        <!-- Lien vers le dashboard -->
                        <li class="nav-item mx-2">
                            <a class="nav-link text-white" href="{{ route('dashboard') }}">Profil</a>
                        </li>

                        <!-- Lien pour ajouter un manga -->
                        <li class="nav-item mx-2">
                            <a class="btn btn-success text-white px-3" href="{{ route('mangas.create') }}">Ajouter un Manga</a>
                        </li>

                        <!-- Lien pour se déconnecter -->
                        <li class="nav-item mx-2">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button class="btn btn-danger text-white px-3" type="submit">Se déconnecter</button>
                            </form>
                        </li>
                    @else
                        <!-- Lien pour se connecter et s'inscrire (si déconnecté) -->
                        <li class="nav-item mx-2">
                            <a class="nav-link text-white" href="{{ route('login') }}">Se connecter</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link text-white" href="{{ route('register') }}">S'inscrire</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main class="py-4 flex-grow-1">
        <div class="container content-container">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Mangaverse - Tous droits réservés</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
