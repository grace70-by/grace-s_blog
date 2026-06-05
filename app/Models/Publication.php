<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Publication extends Model
{
    use HasFactory;
    public const STATUS_DRAFT = 'draft';

    public const STATUS_PUBLISHED = 'published';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'status',
        'meta_description',
        'published_at',
        'views_count',
        'reading_time_minutes',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(PublicationBlock::class)->orderBy('sort_order');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(PublicationReaction::class);
    }

    public function mentions(): HasMany
    {
        return $this->hasMany(PublicationMention::class);
    }

    public function scopeWithLikeMeta(Builder $query): Builder
    {
        $query->withCount('reactions');

        if (auth()->check()) {
            $query->withExists(['reactions as user_has_liked' => fn (Builder $q) => $q->where('user_id', auth()->id())]);
        }

        return $query;
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        $term = trim($term);

        return $query->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('meta_description', 'like', "%{$term}%");
        });
    }

    public function scopeForArchive(Builder $query, int $year, ?int $month = null): Builder
    {
        $query->whereYear('published_at', $year);

        if ($month) {
            $query->whereMonth('published_at', $month);
        }

        return $query;
    }

    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED
            && $this->published_at
            && $this->published_at->lte(now());
    }
}
