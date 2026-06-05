<x-guest-layout>
    <h1 class="text-2xl font-bold text-ig-dark text-center mb-2">Zone sécurisée</h1>
    <p class="text-sm text-ig-muted text-center mb-6">Confirmez votre mot de passe pour continuer.</p>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center py-3">Confirmer</x-primary-button>
    </form>
</x-guest-layout>
