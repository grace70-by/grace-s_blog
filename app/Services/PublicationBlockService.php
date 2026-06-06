<?php

namespace App\Services;

use App\Models\Publication;
use App\Models\PublicationBlock;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PublicationBlockService
{
    public function blocksFromRequest(\Illuminate\Http\Request $request): array
    {
        $blocks = $request->input('blocks', []) ?? [];
        $uploads = $request->file('blocks', []) ?? [];

        foreach (array_keys($blocks) as $index) {
            if (!empty($uploads[$index]['file'])) {
                $blocks[$index]['file'] = $uploads[$index]['file'];
            }
        }

        return $blocks;
    }

    public function sync(Publication $publication, array $blocks): void
    {
        DB::transaction(function () use ($publication, $blocks) {
            $existingPaths = $publication->blocks()->pluck('file_path')->filter()->all();
            $keptPaths = [];

            $publication->blocks()->delete();

            foreach ($blocks as $index => $blockData) {
                $type = $blockData['type'] ?? PublicationBlock::TYPE_TEXT;
                $content = $blockData['content'] ?? [];
                if (is_string($content)) {
                    $content = ['text' => $content];
                }

                $filePath = null;
                if (!empty($blockData['existing_file_path'])) {
                    $filePath = $blockData['existing_file_path'];
                    $keptPaths[] = $filePath;
                } elseif (!empty($blockData['file']) && $blockData['file'] instanceof UploadedFile) {
                    $filePath = $this->storeFile($blockData['file'], $type);
                    $keptPaths[] = $filePath;
                }

                $publication->blocks()->create([
                    'type' => $type,
                    'content' => $content,
                    'file_path' => $filePath,
                    'sort_order' => $index,
                ]);
            }

            foreach ($existingPaths as $path) {
                if (!in_array($path, $keptPaths, true)) {
                    Storage::disk('public')->delete($path);
                }
            }
        });
    }

    public function storeFile(UploadedFile $file, string $type): string
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key' => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => ['secure' => true],
        ]);

        $folder = match ($type) {
            PublicationBlock::TYPE_IMAGE, PublicationBlock::TYPE_GIF => 'publications/images',
            PublicationBlock::TYPE_VIDEO => 'publications/videos',
            PublicationBlock::TYPE_AUDIO => 'publications/audio',
            default => 'publications/files',
        };

        $result = (new UploadApi())->upload(
            $file->getRealPath(),
            ['folder' => $folder]
        );

        return $result['secure_url'];
    }
}
