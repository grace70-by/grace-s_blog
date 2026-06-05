<x-guest-layout :show-logo="false">
    <h1 class="text-2xl font-bold text-ig-dark text-center mb-6">Inscription</h1>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="name" value="Nom complet" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="username" value="Nom d'utilisateur" />
            <x-text-input id="username" type="text" name="username" :value="old('username')" required autocomplete="username" />
            <p class="mt-1 text-xs text-ig-muted">Lettres, chiffres et _ uniquement — pour les @mentions.</p>
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" value="Adresse e-mail" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center py-3">Créer mon compte</x-primary-button>

        <p class="text-center text-sm text-ig-muted pt-2">
            Déjà inscrit ? <a href="{{ route('login') }}" class="text-ig-pink font-semibold hover:underline">Se connecter</a>
        </p>
    </form>
</x-guest-layout>
