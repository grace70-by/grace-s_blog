@extends('layouts.feed')

@section('title', 'Recherche — '.config('app.name'))

@section('content')
    <div class="space-y-4">
        <div class="feed-card p-4">
            <h1 class="text-lg font-bold text-ig-dark mb-4">Recherche</h1>
            <form method="GET" action="{{ route('search') }}" class="flex gap-2">
                <input type="search" name="q" value="{{ $query }}" placeholder="Rechercher des articles…" class="ig-input flex-1" autofocus>
                <button type="submit" class="btn-ig">Rechercher</button>
            </form>
        </div>

        @if ($query === '')
            <div class="feed-card p-8 text-center text-ig-muted">Saisissez un terme de recherche.</div>
        @elseif ($publications->isEmpty())
            <div class="feed-card p-8 text-center text-ig-muted">Aucun résultat pour « {{ $query }} ».</div>
        @else
            <p class="text-sm text-ig-muted px-1">{{ $publications->total() }} résultat(s) pour « {{ $query }} »</p>
            @foreach ($publications as $publication)
                @include('publications._card', ['publication' => $publication])
            @endforeach
            <div>{{ $publications->links() }}</div>
        @endif
    </div>
@endsection
