<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!-- Navbar -->
        <nav class=class="bg-dark text-white text-center py-3">
            <div class="container">
                <a class="navbar-brand fw-bold" href="{{ route('home') }}">Mangaverse</a>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100" style="background-image: url('/images/back.png'); background-size: cover; background-position: center;">
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-dark text-white text-center py-3">
            <p>&copy; 2024 Mangaverse - Tous droits réservés</p>
        </footer>
    </body>
</html>
