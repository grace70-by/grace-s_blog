@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach ([
            ['Publications', $stats['publications_total'], 'dont '.$stats['publications_published'].' publiées'],
            ['Brouillons', $stats['publications_draft'], 'en attente'],
            ['Commentaires', $stats['comments_total'], 'au total'],
            ['Utilisateurs', $stats['users_total'], 'inscrits'],
            ['Signalements', $stats['reports_pending'], 'en attente'],
            ['Vues', number_format($stats['views_total']), 'lectures cumulées'],
        ] as [$label, $value, $sub])
            <div class="feed-card p-4">
                <p class="text-xs uppercase text-ig-muted font-bold">{{ $label }}</p>
                <p class="text-2xl font-bold text-ig-dark mt-1">{{ $value }}</p>
                <p class="text-xs text-ig-muted mt-1">{{ $sub }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="feed-card p-4">
            <h2 class="font-bold text-ig-dark mb-4">Publications récentes</h2>
            <ul class="space-y-3">
                @forelse ($recentPublications as $publication)
                    <li class="flex justify-between text-sm">
                        <a href="{{ route('admin.publications.edit', $publication) }}" class="text-ig-dark hover:text-ig-pink font-medium truncate">{{ $publication->title }}</a>
                        <span class="text-ig-muted shrink-0 ml-2">{{ $publication->status }}</span>
                    </li>
                @empty
                    <li class="text-ig-muted text-sm">Aucune publication.</li>
                @endforelse
            </ul>
        </div>

        <div class="feed-card p-4">
            <h2 class="font-bold text-ig-dark mb-4">Signalements en attente</h2>
            <ul class="space-y-3">
                @forelse ($recentReports as $report)
                    <li class="text-sm">
                        <a href="{{ route('admin.comments.index') }}" class="text-ig-pink hover:underline">Commentaire #{{ $report->comment_id }}</a>
                        <span class="text-ig-muted"> — {{ Str::limit($report->reason, 60) }}</span>
                    </li>
                @empty
                    <li class="text-ig-muted text-sm">Aucun signalement en attente.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
