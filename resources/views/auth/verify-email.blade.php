<x-guest-layout>
    <h1 class="text-2xl font-bold text-ig-dark text-center mb-2">Vérifiez votre e-mail</h1>
    <p class="text-sm text-ig-muted text-center mb-6">
        Un lien de vérification a été envoyé. Consultez votre boîte mail ou demandez un nouvel envoi.
    </p>

    @if (session('status') == 'verification-link-sent')
        <p class="mb-4 text-sm font-medium text-green-600 text-center">Un nouveau lien a été envoyé.</p>
    @endif

    <div class="flex flex-col gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="w-full justify-center py-3">Renvoyer l'e-mail</x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full btn-ig-ghost justify-center py-3">Se déconnecter</button>
        </form>
    </div>
</x-guest-layout>
