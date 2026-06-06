@php
    use App\Models\PublicationBlock;
@endphp

<div class="mb-6">
    @switch($block->type)
        @case(PublicationBlock::TYPE_TEXT)
            <div class="prose max-w-none text-gray-800 whitespace-pre-wrap">{{ $block->content['text'] ?? '' }}</div>
            @break

        @case(PublicationBlock::TYPE_IMAGE)
        @case(PublicationBlock::TYPE_GIF)
            @if ($block->file_path)
                <img src="{{ $block->fileUrl() }}" alt="{{ $block->content['caption'] ?? '' }}" class="rounded-lg max-w-full">
            @elseif (!empty($block->content['url']))
                <img src="{{ $block->content['url'] }}" alt="{{ $block->content['caption'] ?? '' }}" class="rounded-lg max-w-full">
            @endif
            @if (! empty($block->content['caption']))
                <p class="text-sm text-gray-500 mt-2">{{ $block->content['caption'] }}</p>
            @endif
            @break

        @case(PublicationBlock::TYPE_VIDEO)
            @if ($block->file_path)
                <video controls class="w-full rounded-lg">
                    <source src="{{ $block->fileUrl() }}">
                </video>
            @elseif ($block->embedUrl())
                <div class="aspect-video">
                    <iframe src="{{ $block->embedUrl() }}" class="w-full h-full rounded-lg" allowfullscreen></iframe>
                </div>
            @endif
            @if (! empty($block->content['caption']))
                <p class="text-sm text-gray-500 mt-2">{{ $block->content['caption'] }}</p>
            @endif
            @break

        @case(PublicationBlock::TYPE_AUDIO)
            @if ($block->file_path)
                <audio controls class="w-full">
                    <source src="{{ $block->fileUrl() }}">
                </audio>
            @endif
            @if (! empty($block->content['caption']))
                <p class="text-sm text-gray-500 mt-2">{{ $block->content['caption'] }}</p>
            @endif
            @break

        @case(PublicationBlock::TYPE_FILE)
            @if ($block->file_path)
                <a href="{{ $block->fileUrl() }}" class="inline-flex items-center text-indigo-600 hover:underline" download>
                    Télécharger le fichier
                </a>
            @endif
            @break

        @case(PublicationBlock::TYPE_EMBED)
            @if ($block->embedUrl())
                <div class="aspect-video">
                    <iframe src="{{ $block->embedUrl() }}" class="w-full h-full rounded-lg" allowfullscreen></iframe>
                </div>
            @endif
            @break
    @endswitch
</div>
