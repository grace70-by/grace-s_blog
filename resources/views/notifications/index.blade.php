@extends('layouts.feed')

@section('title', 'Notifications — '.config('app.name'))

@section('content')
    <div class="space-y-4">
        <div class="feed-card p-4 flex items-center justify-between">
            <h1 class="text-lg font-bold text-ig-dark">Notifications</h1>
            @if (auth()->user()->unreadNotifications->count())
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="text-sm text-ig-pink font-medium hover:underline">Tout marquer comme lu</button>
                </form>
            @endif
        </div>

        @forelse ($notifications as $notification)
            <div @class(['feed-card p-4', 'border-l-4 border-ig-pink' => is_null($notification->read_at)])>
                <p class="text-sm text-ig-dark">
                    @if (($notification->data['type'] ?? '') === 'mention')
                        <strong>{{ $notification->data['author_name'] ?? 'Quelqu\'un' }}</strong> vous a mentionné sur
                    @elseif (($notification->data['type'] ?? '') === 'publication_tag')
                        <strong>{{ $notification->data['author_name'] ?? 'Quelqu\'un' }}</strong> vous a identifié dans
                    @else
                        <strong>{{ $notification->data['author_name'] ?? 'Quelqu\'un' }}</strong> a répondu sur
                    @endif
                    « {{ $notification->data['publication_title'] ?? 'une publication' }} »
                </p>
                <p class="text-xs text-ig-muted mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                <a href="{{ route('notifications.read', $notification->id) }}" class="inline-block mt-2 text-sm text-ig-pink font-medium hover:underline">
                    Voir →
                </a>
            </div>
        @empty
            <div class="feed-card p-8 text-center text-ig-muted">Aucune notification.</div>
        @endforelse

        <div>{{ $notifications->links() }}</div>
    </div>
@endsection
