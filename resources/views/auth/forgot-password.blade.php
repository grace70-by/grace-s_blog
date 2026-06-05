<x-guest-layout>
    <h1 class="text-2xl font-bold text-ig-dark text-center mb-2">Mot de passe oublié</h1>
    <p class="text-sm text-ig-muted text-center mb-6">
        Indiquez votre e-mail et nous vous enverrons un lien de réinitialisation.
    </p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" value="Adresse e-mail" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center py-3">Envoyer le lien</x-primary-button>
    </form>
</x-guest-layout>
