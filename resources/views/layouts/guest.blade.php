<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }}</title>
        <x-app-favicon />
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased min-h-screen bg-ig-gradient-soft flex flex-col items-center justify-center p-4">
        <div class="w-full max-w-md">
            @if ($showLogo)
                <a href="{{ route('home') }}" class="flex flex-col items-center mb-8">
                    <x-app-icon size="xl" class="rounded-2xl shadow-lg" />
                    <span class="mt-3 text-2xl font-bold bg-ig-gradient bg-clip-text text-transparent">{{ config('app.name') }}</span>
                </a>
            @endif

            <div class="feed-card p-8 shadow-card-hover border-ig-border/80">
                {{ $slot }}
            </div>

            <p class="text-center text-sm text-ig-muted mt-6">
                <a href="{{ route('home') }}" class="hover:text-ig-pink transition">← Retour au fil d'actualité</a>
            </p>
        </div>
    </body>
</html>
