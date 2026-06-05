<?php

namespace App\Services;

use App\Models\Publication;
use App\Models\PublicationBlock;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PublicationService
{
    public function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $base = Str::slug($title) ?: 'article';
        $slug = $base;
        $counter = 1;

        while ($this->slugExists($slug, $excludeId)) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    public function syncComputedFields(Publication $publication): void
    {
        $publication->loadMissing('blocks');

        $publication->reading_time_minutes = $this->readingTimeMinutes($publication->blocks);
        $publication->meta_description = Str::limit($this->excerptFromBlocks($publication->blocks), 160, '');

        $publication->saveQuietly();
    }

    public function excerptFromBlocks(Collection $blocks, int $limit = 200): string
    {
        foreach ($blocks as $block) {
            if ($block->type === PublicationBlock::TYPE_TEXT) {
                $text = trim(strip_tags($block->content['text'] ?? ''));

                if ($text !== '') {
                    return Str::limit($text, $limit);
                }
            }
        }

        return '';
    }

    public function coverUrl(Publication $publication): ?string
    {
        $publication->loadMissing('blocks');

        foreach ($publication->blocks as $block) {
            if (in_array($block->type, [PublicationBlock::TYPE_IMAGE, PublicationBlock::TYPE_GIF], true) && $block->file_path) {
                return $block->fileUrl();
            }
        }

        return null;
    }

    public function readingTimeMinutes(Collection $blocks): int
    {
        $words = 0;

        foreach ($blocks as $block) {
            if ($block->type === PublicationBlock::TYPE_TEXT) {
                $text = strip_tags($block->content['text'] ?? '');
                $words += str_word_count($text);
            }
        }

        return max(1, (int) ceil($words / 200));
    }

    public function related(Publication $publication, int $limit = 3): Collection
    {
        return Publication::published()
            ->where('id', '!=', $publication->id)
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function incrementViews(Publication $publication): void
    {
        $publication->increment('views_count');
    }

    private function slugExists(string $slug, ?int $excludeId): bool
    {
        $query = Publication::where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
