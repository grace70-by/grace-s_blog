@extends('layouts.feed')

@section('title', 'Fil d\'actualité — '.config('app.name'))

@section('content')
    <div class="space-y-4">
        <div class="feed-card px-4 py-3">
            <form method="GET" action="{{ route('search') }}" class="flex gap-2">
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Rechercher des articles…"
                       class="ig-input flex-1">
                <button type="submit" class="btn-ig shrink-0">Rechercher</button>
            </form>
        </div>

        <div class="feed-card px-4 py-3 flex items-center justify-between">
            <h1 class="text-lg font-bold text-ig-dark">Fil d'actualité</h1>
            <span class="text-xs text-ig-muted font-medium">{{ $publications->total() }} publication(s)</span>
        </div>

        @if ($publications->isEmpty())
            <div class="feed-card p-10 text-center">
                <p class="text-ig-muted">Aucune publication pour le moment.</p>
                @auth
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.publications.create') }}" class="inline-block mt-4 btn-ig">Créer une publication</a>
                    @endif
                @endauth
            </div>
        @else
            @foreach ($publications as $publication)
                @include('publications._card', ['publication' => $publication])
            @endforeach

            <div class="pt-2">{{ $publications->links() }}</div>
        @endif
    </div>
@endsection
