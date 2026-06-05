@extends('layouts.feed', ['narrow' => true])

@php
    $articleUrl = route('publications.show', $publication);
    $coverUrl = $publicationService->coverUrl($publication);
    $metaDescription = $publication->meta_description ?: $publicationService->excerptFromBlocks($publication->blocks);
@endphp

@section('title', $publication->title.' — '.config('app.name'))

@push('head')
    <x-seo-meta
        :title="$publication->title.' — '.config('app.name')"
        :description="$metaDescription"
        :url="$articleUrl"
        :image="$coverUrl ?: asset('images/app-icon.png')"
        type="article"
    />
@endpush

@section('content')
    <div class="space-y-4">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-1 text-sm font-medium text-ig-pink hover:underline">
            ← Retour au fil
        </a>

        <article class="feed-card">
            <div class="feed-card-header">
                <x-avatar :user="$publication->author" size="lg" />
                <div>
                    <p class="font-bold text-ig-dark">
                        <a href="{{ route('authors.show', $publication->author) }}" class="hover:text-ig-pink">{{ $publication->author->name }}</a>
                    </p>
                    <p class="text-sm text-ig-muted">
                        {{ $publication->published_at?->translatedFormat('d F Y') }}
                        @if ($publication->reading_time_minutes)
                            · {{ $publication->reading_time_minutes }} min de lecture
                        @endif
                        · {{ number_format($publication->views_count) }} vues
                    </p>
                    @if ($publication->mentions->isNotEmpty())
                        <p class="text-xs text-ig-dark mt-1">
                            avec 
                            @foreach ($publication->mentions as $mention)
                                <a href="{{ route('authors.show', $mention->mentionedUser) }}" class="font-medium hover:text-ig-pink focus:outline-none focus:underline hover:underline transition">
                                    {{ $mention->mentionedUser->name }}
                                </a>@if(!$loop->last), @endif
                            @endforeach
                        </p>
                    @endif
                </div>
            </div>

            <div class="feed-card-body">
                <h1 class="text-2xl font-bold text-ig-dark mb-4">{{ $publication->title }}</h1>

                @foreach ($publication->blocks as $block)
                    @include('publications.blocks.render', ['block' => $block])
                @endforeach
            </div>

            <div class="px-4 py-3 border-t border-ig-border flex flex-wrap items-center gap-2">
                <x-publication-like-button :publication="$publication" />
            </div>

            <div class="px-4 pb-4 border-t border-ig-border pt-4">
                <p class="text-xs font-bold uppercase text-ig-muted mb-2">Partager</p>
                <x-share-buttons :url="$articleUrl" :title="$publication->title" />
            </div>
        </article>

        @if ($related->isNotEmpty())
            <section class="feed-card p-4">
                <h2 class="font-bold text-ig-dark mb-4">Articles similaires</h2>
                <div class="space-y-3">
                    @foreach ($related as $item)
                        <a href="{{ route('publications.show', $item) }}" class="block p-3 rounded-xl border border-ig-border hover:border-ig-pink/30 transition">
                            <p class="font-semibold text-ig-dark">{{ $item->title }}</p>
                            <p class="text-xs text-ig-muted mt-1">{{ $item->published_at?->translatedFormat('d M Y') }}</p>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <section id="comments" class="feed-card overflow-visible">
            <div class="px-4 py-3 border-b border-ig-border flex flex-wrap items-center justify-between gap-3 bg-ig-surface/50">
                <h2 class="font-bold text-ig-dark">Commentaires</h2>
                <div class="flex gap-2">
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'recent']) }}#comments"
                       @class(['sort-pill', $sort === 'recent' ? 'sort-pill-active' : 'sort-pill-inactive'])>
                        Récents
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}#comments"
                       @class(['sort-pill', $sort === 'popular' ? 'sort-pill-active' : 'sort-pill-inactive'])>
                        Populaires
                    </a>
                </div>
            </div>

            <div class="p-4">
                @auth
                    <form method="POST" action="{{ route('comments.store', $publication) }}" class="mb-6">
                        @csrf
                        <div class="flex gap-3">
                            <x-avatar :user="auth()->user()" />
                            <div class="flex-1">
                                <textarea name="body" rows="3" required maxlength="5000"
                                          class="ig-input resize-none"
                                          placeholder="Ajouter un commentaire… @username">{{ old('body') }}</textarea>
                                <x-input-error :messages="$errors->get('body')" class="mt-2" />
                                <div class="mt-2 flex justify-end">
                                    <x-primary-button>Publier</x-primary-button>
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="mb-6 p-4 rounded-xl border border-ig-border bg-ig-card text-center">
                        <a href="{{ route('login') }}" class="btn-ig inline-flex">Se connecter pour commenter</a>
                    </div>
                @endauth

                @include('comments._thread', ['comments' => $comments, 'depth' => 0, 'commentService' => $commentService])
            </div>
        </section>
    </div>
@endsection
