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
    <body class="font-sans antialiased min-h-screen bg-ig-gradient-soft">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-ig-card border-b border-ig-border">
                <div class="max-w-3xl mx-auto py-5 px-4">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="py-8">
            <div class="max-w-3xl mx-auto px-4">
                <x-flash-messages />
                {{ $slot }}
            </div>
        </main>
        @stack('scripts')
    </body>
</html>
