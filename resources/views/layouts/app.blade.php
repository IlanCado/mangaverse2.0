<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mangaverse')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome pour les étoiles interactives -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-image: url('/images/back.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        .content-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Navbar styling */
        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9);
        }

        .navbar-dark .navbar-nav .nav-link:hover {
            color: #fff;
        }

        /* Footer styling */
        footer {
            background-color: #343a40;
        }

        /* Styles pour les étoiles interactives */
        .rating-stars {
            display: flex;
            gap: 5px;
        }

        .rating-stars input {
            display: none;
        }

        .rating-stars label {
            font-size: 2rem;
            color: #ccc;
            cursor: pointer;
            transition: color 0.2s;
        }

        .rating-stars input:checked ~ label,
        .rating-stars label:hover,
        .rating-stars label:hover ~ label {
            color: #ffc107;
        }

        /* Active buttons for logged-out users */
        .btn-success, .btn-danger {
            font-weight: bold;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <!-- Logo + Brand -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 30px; height: 30px; margin-right: 10px;">
            <span>Mangaverse</span>
        </a>
        
        <!-- Navbar Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item mx-2">
                    <a class="nav-link text-white" href="{{ route('home') }}">Explorer les Mangas</a>
                </li>
                <!-- Lien GLPI  -->
                <li class="nav-item mx-2">
                    <a class="nav-link text-white" href="https://support.mangaverse.blog:50274" target="_blank">Support</a>
                </li>
                @auth
                    <li class="nav-item mx-2">
                        <a class="nav-link text-white" href="{{ route('dashboard') }}">Profil</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="btn btn-success text-white px-3" href="{{ route('mangas.create') }}">Ajouter un Manga</a>
                    </li>
                    <li class="nav-item mx-2">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button class="btn btn-danger text-white px-3" type="submit">Se déconnecter</button>
                        </form>
                    </li>
                    @if(auth()->user()->is_admin)
                        <li class="nav-item mx-2">
                            <a class="nav-link text-warning fw-bold" href="{{ route('admin.index') }}">Administration</a>
                        </li>
                    @endif
                @else
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
