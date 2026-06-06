@php
    use App\Models\PublicationBlock;
@endphp

<div class="overflow-hidden">
    @switch($block->type)
        @case(PublicationBlock::TYPE_TEXT)
            @if (! empty(trim($block->content['text'] ?? '')))
                <p class="px-4 py-3 text-sm text-ig-dark leading-relaxed line-clamp-4 whitespace-pre-wrap">{{ $block->content['text'] }}</p>
            @endif
            @break

        @case(PublicationBlock::TYPE_IMAGE)
        @case(PublicationBlock::TYPE_GIF)
            <div class="article-image-container overflow-hidden w-full">
                @if ($block->file_path)
                    <img src="{{ $block->fileUrl() }}" alt="{{ $block->content['caption'] ?? '' }}" class="max-w-full w-full h-auto max-h-80 object-cover block overflow-hidden">
                @elseif (!empty($block->content['url']))
                    <img src="{{ $block->content['url'] }}" alt="{{ $block->content['caption'] ?? '' }}" class="max-w-full w-full h-auto max-h-80 object-cover block overflow-hidden">
                @endif
            </div>
            @break

        @case(PublicationBlock::TYPE_VIDEO)
            @if ($block->file_path)
                <video controls class="w-full max-h-80" preload="metadata">
                    <source src="{{ $block->fileUrl() }}">
                </video>
            @elseif ($block->embedUrl())
                <div class="aspect-video bg-black">
                    <iframe src="{{ $block->embedUrl() }}" class="w-full h-full" allowfullscreen loading="lazy"></iframe>
                </div>
            @endif
            @break

        @case(PublicationBlock::TYPE_AUDIO)
            @if ($block->file_path)
                <div class="px-4 py-3">
                    <audio controls class="w-full">
                        <source src="{{ $block->fileUrl() }}">
                    </audio>
                </div>
            @endif
            @break

        @case(PublicationBlock::TYPE_EMBED)
            @if ($block->embedUrl())
                @if(str_contains($block->embedUrl(), 'unsplash.com') || preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $block->embedUrl()))
                    <div class="article-image-container overflow-hidden w-full">
                        <img src="{{ $block->embedUrl() }}" class="max-w-full w-full h-auto max-h-80 object-cover block overflow-hidden">
                    </div>
                @else
                    <div class="aspect-video bg-black overflow-hidden w-full">
                        <iframe src="{{ $block->embedUrl() }}" class="w-full h-full border-0 overflow-hidden" allowfullscreen loading="lazy" scrolling="no"></iframe>
                    </div>
                @endif
            @endif
            @break
    @endswitch
</div>
