<x-guest-layout>
    <div class="container">
        <h1 class="text-center mb-4">Inscription</h1>
        <form method="POST" action="{{ route('register') }}" class="bg-white p-4 rounded shadow">
            @csrf

            <!-- Name -->
            <div class="mb-3">
                <x-input-label for="name" :value="__('Nom')" />
                <x-text-input id="name" class="block mt-1 w-full form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
            </div>

            <!-- Email Address -->
            <div class="mb-3">
                <x-input-label for="email" :value="__('Adresse e-mail')" />
                <x-text-input id="email" class="block mt-1 w-full form-control" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
            </div>

            <!-- Password -->
            <div class="mb-3">
                <x-input-label for="password" :value="__('Mot de passe')" />

                <x-text-input id="password" class="block mt-1 w-full form-control"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <x-input-label for="password_confirmation" :value="__('Confirmez le mot de passe')" />

                <x-text-input id="password_confirmation" class="block mt-1 w-full form-control"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Déjà inscrit ?') }}
                </a>

                <x-primary-button class="btn btn-primary ms-4">
                    {{ __('S\'inscrire') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
