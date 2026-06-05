@extends('layouts.admin')

@section('title', 'Modération des commentaires')

@section('content')
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('admin.comments.index') }}" @class(['sort-pill', $filter === 'all' ? 'sort-pill-active' : 'sort-pill-inactive'])>Tous</a>
        <a href="{{ route('admin.comments.index', ['filter' => 'reported']) }}" @class(['sort-pill', $filter === 'reported' ? 'sort-pill-active' : 'sort-pill-inactive'])>Signalés</a>
        <a href="{{ route('admin.comments.index', ['filter' => 'hidden']) }}" @class(['sort-pill', $filter === 'hidden' ? 'sort-pill-active' : 'sort-pill-inactive'])>Masqués</a>
    </div>

    <h3 class="font-bold text-ig-dark mb-4">Signalements en attente</h3>
    @if ($pendingReports->isEmpty())
        <p class="text-ig-muted text-sm mb-8">Aucun signalement en attente.</p>
    @else
        <div class="feed-card mb-8 divide-y divide-ig-border">
            @foreach ($pendingReports as $report)
                <div class="p-4">
                    <p class="text-sm text-ig-muted">
                        Signalé par <strong class="text-ig-dark">{{ $report->reporter->name }}</strong> —
                        <a href="{{ route('publications.show', $report->comment->publication) }}#comments" class="text-ig-pink font-medium hover:underline" target="_blank">Voir l'article</a>
                    </p>
                    <blockquote class="mt-2 text-ig-dark border-s-4 border-ig-pink/40 ps-3 text-sm">{{ Str::limit($report->comment->body, 200) }}</blockquote>
                    <p class="mt-2 text-sm"><strong>Motif :</strong> {{ $report->reason }}</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <form method="POST" action="{{ route('admin.reports.update', $report) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="reviewed">
                            <button type="submit" class="text-xs px-3 py-1.5 rounded-lg bg-green-100 text-green-800 font-semibold">Traité</button>
                        </form>
                        <form method="POST" action="{{ route('admin.reports.update', $report) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="dismissed">
                            <button type="submit" class="text-xs px-3 py-1.5 rounded-lg bg-ig-hover text-ig-muted font-semibold">Rejeter</button>
                        </form>
                        <form method="POST" action="{{ route('admin.comments.hide', $report->comment) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs px-3 py-1.5 rounded-lg bg-amber-100 text-amber-800 font-semibold">Masquer</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $pendingReports->links() }}
    @endif

    <h3 class="font-bold text-ig-dark mb-4">Commentaires</h3>
    <div class="feed-card divide-y divide-ig-border">
        @forelse ($comments as $comment)
            <div @class(['p-4', 'bg-amber-50/50' => $comment->hidden_at, 'opacity-60' => $comment->trashed()])>
                <div class="flex justify-between gap-2">
                    <div>
                        <span class="font-semibold text-ig-dark">{{ $comment->user?->name ?? '—' }}</span>
                        <span class="text-ig-muted text-sm">@{{ $comment->user?->username }}</span>
                        @if ($comment->hidden_at)
                            <span class="ms-2 text-xs bg-amber-200 text-amber-900 px-2 py-0.5 rounded-full font-medium">Masqué</span>
                        @endif
                    </div>
                    <span class="text-xs text-ig-muted shrink-0">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <p class="mt-2 text-sm text-ig-dark">{{ Str::limit($comment->body, 300) }}</p>
                <p class="text-xs text-ig-muted mt-1">Article : {{ $comment->publication->title }}</p>
                <div class="mt-3 flex gap-3">
                    @if ($comment->hidden_at)
                        <form method="POST" action="{{ route('admin.comments.unhide', $comment) }}">@csrf @method('PATCH')
                            <button type="submit" class="text-sm font-medium text-green-600 hover:underline">Réafficher</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.comments.hide', $comment) }}">@csrf @method('PATCH')
                            <button type="submit" class="text-sm font-medium text-amber-600 hover:underline">Masquer</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.comments.force-delete', $comment) }}" onsubmit="return confirm('Suppression définitive ?')">@csrf @method('DELETE')
                        <button type="submit" class="text-sm font-medium text-red-500 hover:underline">Supprimer</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="p-6 text-ig-muted text-center">Aucun commentaire.</p>
        @endforelse
    </div>
    <div class="mt-4">{{ $comments->links() }}</div>
@endsection
