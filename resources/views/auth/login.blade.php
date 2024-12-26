@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Connexion</h1>
    <form method="POST" action="{{ route('login') }}" class="bg-white p-4 rounded shadow">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Adresse e-mail') }}</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Mot de passe') }}</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
            @error('password')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-3 form-check">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label">{{ __('Se souvenir de moi') }}</label>
        </div>

        <!-- Buttons -->
        <div class="d-flex justify-content-between align-items-center">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none">{{ __('Mot de passe oublié ?') }}</a>
            @endif
            <button type="submit" class="btn btn-primary">{{ __('Se connecter') }}</button>
        </div>
    </form>
</div>
@endsection
