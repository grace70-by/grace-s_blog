@php
    $publicationService = $publicationService ?? app(\App\Services\PublicationService::class);
    $excerpt = $publicationService->excerptFromBlocks($publication->blocks);
    $previewBlocks = $publication->blocks->filter(function ($block) {
        return match ($block->type) {
            'image', 'gif', 'audio', 'file' => (bool) $block->file_path,
            'video' => (bool) $block->file_path || filled($block->content['url'] ?? null),
            'embed' => filled($block->content['url'] ?? null),
            default => false,
        };
    });
@endphp

<article class="feed-card hover:shadow-card-hover transition-shadow duration-200">
    <div class="feed-card-header">
        <x-avatar :user="$publication->author" />
        <div class="min-w-0 flex-1">
            <p class="font-semibold text-ig-dark truncate">
                <a href="{{ route('authors.show', $publication->author) }}" class="hover:text-ig-pink">{{ $publication->author->name }}</a>
                @if ($publication->mentions->isNotEmpty())
                    <span class="text-xs font-normal text-ig-muted">
                        avec 
                        @foreach ($publication->mentions as $mention)
                            <a href="{{ route('authors.show', $mention->mentionedUser) }}" class="hover:text-ig-pink hover:underline transition">{{ $mention->mentionedUser->name }}</a>@if(!$loop->last), @endif
                        @endforeach
                    </span>
                @endif
            </p>
            <p class="text-xs text-ig-muted">
                {{ $publication->published_at?->diffForHumans() }}
                @if ($publication->reading_time_minutes)
                    · {{ $publication->reading_time_minutes }} min
                @endif
            </p>
        </div>
    </div>

    <div class="feed-card-body">
        <h2 class="text-xl font-bold text-ig-dark mb-2">
            <a href="{{ route('publications.show', $publication) }}" class="hover:text-ig-pink transition">
                {{ $publication->title }}
            </a>
        </h2>

        @if ($excerpt)
            <p class="text-sm text-ig-muted leading-relaxed mb-3">{{ $excerpt }}</p>
        @endif
    </div>

    @if ($previewBlocks->isNotEmpty())
        <a href="{{ route('publications.show', $publication) }}" class="block border-t border-ig-border">
            @foreach ($previewBlocks->take(2) as $block)
                @include('publications.blocks.preview', ['block' => $block])
            @endforeach
        </a>
    @endif

    <div class="feed-action-bar">
        <x-publication-like-button :publication="$publication" />
        <a href="{{ route('publications.show', $publication) }}#comments" class="feed-action-btn">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            Commenter
        </a>
        <a href="{{ route('publications.show', $publication) }}" class="feed-action-btn">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            Lire · {{ number_format($publication->views_count) }} vues
        </a>
    </div>
</article>
