<section>
    <header class="mb-6">
        <h2 class="text-lg font-bold text-ig-dark">Informations du profil</h2>
        <p class="mt-1 text-sm text-ig-muted">Mettez à jour votre nom, identifiant et adresse e-mail.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" value="Nom complet" />
            <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="username" value="Nom d'utilisateur" />
            <x-text-input id="username" name="username" type="text" :value="old('username', $user->username)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label for="email" value="Adresse e-mail" />
            <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-ig-muted">
                        Votre e-mail n'est pas vérifié.
                        <button form="send-verification" class="text-ig-pink font-medium hover:underline">Renvoyer le lien</button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-green-600 font-medium">Un nouveau lien a été envoyé.</p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="bio" value="Bio (visible sur votre profil public)" />
            <textarea id="bio" name="bio" rows="3" maxlength="1000" class="ig-input mt-1">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button>Enregistrer</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-medium">Enregistré.</p>
            @endif
        </div>
    </form>
</section>
