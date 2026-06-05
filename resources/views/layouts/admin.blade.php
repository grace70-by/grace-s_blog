<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — {{ config('app.name') }}</title>
    <x-app-favicon />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen bg-ig-gradient-soft">
    <div class="h-1 bg-ig-gradient-nav fixed top-0 left-0 right-0 z-50"></div>
    <div class="min-h-screen flex pt-1">
        <aside class="w-64 bg-ig-dark text-white shrink-0 hidden md:flex flex-col">
            <div class="p-6 border-b border-white/10">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <x-app-icon size="sm" class="rounded-lg" />
                    <span class="font-bold">Administration</span>
                </a>
            </div>
            <nav class="p-4 space-y-1 flex-1">
                <a href="{{ route('admin.dashboard') }}" @class(['flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition', request()->routeIs('admin.dashboard') ? 'bg-ig-gradient text-white' : 'text-white/70 hover:bg-white/10 hover:text-white'])>
                    Tableau de bord
                </a>
                <a href="{{ route('admin.publications.index') }}" @class(['flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition', request()->routeIs('admin.publications.*') ? 'bg-ig-gradient text-white' : 'text-white/70 hover:bg-white/10 hover:text-white'])>
                    Publications
                </a>
                <a href="{{ route('admin.comments.index') }}" @class(['flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition', request()->routeIs('admin.comments.*') || request()->routeIs('admin.reports.*') ? 'bg-ig-gradient text-white' : 'text-white/70 hover:bg-white/10 hover:text-white'])>
                    Modération
                </a>
                <a href="{{ route('admin.users.index') }}" @class(['flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition', request()->routeIs('admin.users.*') ? 'bg-ig-gradient text-white' : 'text-white/70 hover:bg-white/10 hover:text-white'])>
                    Utilisateurs
                </a>
                <a href="{{ route('admin.pages.index') }}" @class(['flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition', request()->routeIs('admin.pages.*') ? 'bg-ig-gradient text-white' : 'text-white/70 hover:bg-white/10 hover:text-white'])>
                    Pages
                </a>
            </nav>
            <div class="p-4 border-t border-white/10 text-sm text-white/60">
                {{ auth()->user()->name }}
            </div>
        </aside>
        <div class="flex-1 min-w-0">
            <header class="bg-ig-card border-b border-ig-border px-6 py-4 flex justify-between items-center sticky top-0 z-40">
                <h1 class="text-lg font-bold text-ig-dark">@yield('title', 'Admin')</h1>
                <a href="{{ route('home') }}" class="text-sm font-medium text-ig-pink hover:underline">← Retour au site</a>
            </header>
            <main class="p-6 max-w-5xl">
                <x-flash-messages />
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
