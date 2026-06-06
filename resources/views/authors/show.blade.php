@extends('layouts.feed')

@section('title', $user->name.' — '.config('app.name'))

@section('content')
    <div class="space-y-4">
        <div class="feed-card p-6">
            <div class="flex gap-4 items-start">
                <x-avatar :user="$user" size="lg" />
                <div>
                    <h1 class="text-xl font-bold text-ig-dark">{{ $user->name }}</h1>
                    <p class="text-sm text-ig-muted">@{{ $user->username }}</p>
                    @if ($user->bio)
                        <p class="mt-3 text-sm text-ig-dark leading-relaxed">{{ $user->bio }}</p>
                    @endif
                    <p class="mt-2 text-xs text-ig-muted">{{ $publications->total() }} publication(s)</p>
                </div>
            </div>
        </div>

        @forelse ($publications as $publication)
            @include('publications._card', ['publication' => $publication])
        @empty
            <div class="feed-card p-8 text-center text-ig-muted">Aucune publication publiée.</div>
        @endforelse

        <div>{{ $publications->onEachSide(1)->links() }}</div>
    </div>
@endsection
