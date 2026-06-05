<nav x-data="{ open: false }" class="sticky top-0 z-50 bg-ig-card/95 backdrop-blur-md border-b border-ig-border shadow-sm">
    <div class="h-1 bg-ig-gradient-nav"></div>
    <div class="max-w-[1200px] mx-auto px-4">
        <div class="flex justify-between items-center h-14">
            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
                    <x-app-icon size="sm" />
                    <span class="hidden sm:block text-lg font-bold bg-ig-gradient bg-clip-text text-transparent">{{ config('app.name') }}</span>
                </a>
                <div class="hidden md:flex items-center gap-1">
                    <form method="GET" action="{{ route('search') }}" class="relative mr-2">
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher…"
                               class="w-44 lg:w-56 rounded-lg border border-ig-border bg-ig-surface px-3 py-1.5 text-sm focus:border-ig-pink focus:ring-ig-pink">
                    </form>
                    <a href="{{ route('home') }}" @class(['px-4 py-2 rounded-lg text-sm font-semibold transition', request()->routeIs('home') ? 'bg-ig-hover text-ig-pink' : 'text-ig-muted hover:bg-ig-hover hover:text-ig-dark'])>
                        Fil d'actualité
                    </a>
                    <a href="{{ route('archives.index') }}" @class(['px-4 py-2 rounded-lg text-sm font-semibold transition', request()->routeIs('archives.*') ? 'bg-ig-hover text-ig-pink' : 'text-ig-muted hover:bg-ig-hover hover:text-ig-dark'])>
                        Archives
                    </a>
                    @auth
                        @if (Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" @class(['px-4 py-2 rounded-lg text-sm font-semibold transition', request()->routeIs('admin.*') ? 'bg-ig-hover text-ig-pink' : 'text-ig-muted hover:bg-ig-hover'])>
                                Admin
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="btn-ig-ghost">Connexion</a>
                    <a href="{{ route('register') }}" class="btn-ig">S'inscrire</a>
                @else
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-2 py-1 rounded-lg hover:bg-ig-hover transition">
                        <x-avatar :user="Auth::user()" size="sm" />
                        <span class="text-sm font-medium text-ig-dark hidden lg:inline">{{ Auth::user()->name }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-ig-ghost text-ig-muted">Déconnexion</button>
                    </form>
                @endguest
            </div>

            <button @click="open = !open" class="sm:hidden p-2 rounded-lg text-ig-muted hover:bg-ig-hover">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': !open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="open" x-cloak class="sm:hidden border-t border-ig-border bg-ig-card px-4 py-3 space-y-1">
        <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg font-medium text-ig-dark hover:bg-ig-hover">Fil d'actualité</a>
        @auth
            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-lg text-ig-dark hover:bg-ig-hover">Mon profil</a>
            @if (Auth::user()->isAdmin())
                <a href="{{ route('admin.publications.index') }}" class="block px-3 py-2 rounded-lg text-ig-dark hover:bg-ig-hover">Administration</a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-ig-muted hover:bg-ig-hover">Déconnexion</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-ig-dark">Connexion</a>
            <a href="{{ route('register') }}" class="block px-3 py-2 rounded-lg text-ig-pink font-semibold">S'inscrire</a>
        @endauth
    </div>
</nav>
