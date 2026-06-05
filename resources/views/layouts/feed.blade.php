<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <x-app-favicon />
    @unless(View::hasSection('head'))
        <x-seo-meta />
    @endunless
    @stack('head')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen bg-ig-gradient-soft">
    @include('layouts.navigation')

    <div class="max-w-[1200px] mx-auto px-4 py-6">
        <x-flash-messages />

        @php($narrow = $narrow ?? false)

        <div @class([
            'feed-shell',
            'feed-shell--narrow' => $narrow,
        ])>
            @unless($narrow)
                <aside class="feed-sidebar-left hidden lg:block space-y-4">
                    <div class="feed-card p-3 sticky top-20">
                        <p class="text-xs font-bold uppercase tracking-wide text-ig-muted px-2 mb-2">Menu</p>
                        <nav class="space-y-1">
                            <a href="{{ route('home') }}" @class(['flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition', request()->routeIs('home') ? 'bg-ig-gradient text-white shadow-md' : 'text-ig-dark hover:bg-ig-hover'])>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Fil d'actualité
                            </a>
                            <a href="{{ route('archives.index') }}" @class(['flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition', request()->routeIs('archives.*') ? 'bg-ig-gradient text-white shadow-md' : 'text-ig-dark hover:bg-ig-hover'])>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Archives
                            </a>
                            <a href="{{ route('pages.show', 'a-propos') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-ig-dark hover:bg-ig-hover font-medium transition">
                                À propos
                            </a>
                            @auth
                                <a href="{{ route('notifications.index') }}" @class(['flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium transition', request()->routeIs('notifications.*') ? 'bg-ig-gradient text-white shadow-md' : 'text-ig-dark hover:bg-ig-hover'])>
                                    Notifications
                                    @if (Auth::user()->unreadNotifications->count())
                                        <span class="ml-auto text-xs bg-ig-pink text-white px-2 py-0.5 rounded-full">{{ Auth::user()->unreadNotifications->count() }}</span>
                                    @endif
                                </a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-ig-dark hover:bg-ig-hover font-medium transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Mon profil
                                </a>
                                @if (Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-ig-dark hover:bg-ig-hover font-medium transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Administration
                                    </a>
                                @endif
                            @endauth
                        </nav>
                    </div>
                </aside>
            @endunless

            <div @class(['feed-main', 'max-w-feed mx-auto w-full' => $narrow])>
                @yield('content')
            </div>

            @unless($narrow)
                @guest
                    <aside class="feed-sidebar-right hidden lg:block space-y-4">
                        <div class="feed-card p-4 bg-ig-gradient text-white sticky top-20">
                            <h3 class="font-bold mb-2">Rejoignez la communauté</h3>
                            <p class="text-sm text-white/90 mb-4">Connectez-vous pour commenter et réagir.</p>
                            <a href="{{ route('register') }}" class="block text-center py-2.5 rounded-lg bg-white text-ig-pink font-semibold text-sm hover:bg-white/90 transition">
                                Créer un compte
                            </a>
                            <a href="{{ route('login') }}" class="block text-center mt-2 py-2 text-sm text-white/90 hover:underline">
                                Se connecter
                            </a>
                        </div>
                    </aside>
                @endguest
            @endunless
        </div>
    </div>

    @stack('scripts')
</body>
</html>
