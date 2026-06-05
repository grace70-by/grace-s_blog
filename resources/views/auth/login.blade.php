<x-guest-layout>
    <h1 class="text-2xl font-bold text-ig-dark text-center mb-6">Connexion</h1>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" value="Adresse e-mail" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center">
            <input id="remember_me" type="checkbox" class="rounded border-ig-border text-ig-pink focus:ring-ig-pink/30" name="remember">
            <label for="remember_me" class="ms-2 text-sm text-ig-muted">Se souvenir de moi</label>
        </div>

        <x-primary-button class="w-full justify-center py-3">Se connecter</x-primary-button>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-2 pt-2 text-sm">
            @if (Route::has('password.request'))
                <a class="text-ig-pink hover:underline font-medium" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
            @endif
            <a class="text-ig-muted hover:text-ig-pink" href="{{ route('register') }}">Créer un compte</a>
        </div>
    </form>
</x-guest-layout>
