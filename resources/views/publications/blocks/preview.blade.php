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
            @if ($block->file_path)
                <img src="{{ $block->fileUrl() }}" alt="{{ $block->content['caption'] ?? '' }}" class="w-full max-h-80 object-cover">
            @endif
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
                <div class="aspect-video bg-black">
                    <iframe src="{{ $block->embedUrl() }}" class="w-full h-full" allowfullscreen loading="lazy"></iframe>
                </div>
            @endif
            @break
    @endswitch
</div>
