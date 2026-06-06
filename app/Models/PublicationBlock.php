<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PublicationBlock extends Model
{
    use HasFactory;
    public const TYPE_TEXT = 'text';

    public const TYPE_IMAGE = 'image';

    public const TYPE_VIDEO = 'video';

    public const TYPE_AUDIO = 'audio';

    public const TYPE_GIF = 'gif';

    public const TYPE_FILE = 'file';

    public const TYPE_EMBED = 'embed';

    public const TYPES = [
        self::TYPE_TEXT,
        self::TYPE_IMAGE,
        self::TYPE_VIDEO,
        self::TYPE_AUDIO,
        self::TYPE_GIF,
        self::TYPE_FILE,
        self::TYPE_EMBED,
    ];

    protected $fillable = [
        'publication_id',
        'type',
        'content',
        'file_path',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'content' => 'array',
        ];
    }

    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class);
    }

    public function fileUrl(): ?string
    {
        if (! $this->file_path) {
            return null;
        }

        // Si c'est déjà une URL complète (Cloudinary), la retourner directement
        if (str_starts_with($this->file_path, 'http')) {
            return $this->file_path;
        }

        // Sinon, URL locale (legacy)
        return asset('storage/'.$this->file_path);
    }

    public function embedUrl(): ?string
    {
        $url = trim($this->content['url'] ?? '');

        if ($url === '') {
            return null;
        }

        if (preg_match('/(?:youtube\.com\/watch\?v=|youtube\.com\/embed\/|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/'.$matches[1];
        }

        if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
            return 'https://player.vimeo.com/video/'.$matches[1];
        }

        return $url;
    }
}
