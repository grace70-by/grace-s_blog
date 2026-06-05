<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePublicationRequest;
use App\Http\Requests\UpdatePublicationRequest;
use App\Models\Publication;
use App\Services\PublicationBlockService;
use App\Services\PublicationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PublicationController extends Controller
{
    public function __construct(
        private PublicationBlockService $blockService,
        private PublicationService $publicationService
    ) {}

    public function index(): View
    {
        $publications = Publication::with('author')
            ->latest()
            ->paginate(15);

        return view('admin.publications.index', compact('publications'));
    }

    public function create(): View
    {
        return view('admin.publications.create');
    }

    public function store(StorePublicationRequest $request): RedirectResponse
    {
        $publication = $this->savePublication(new Publication, $request);

        return redirect()
            ->route('admin.publications.edit', $publication)
            ->with('success', 'Publication créée.');
    }

    public function edit(Publication $publication): View
    {
        $publication->load('blocks');

        return view('admin.publications.edit', compact('publication'));
    }

    public function update(UpdatePublicationRequest $request, Publication $publication): RedirectResponse
    {
        $this->savePublication($publication, $request);

        return redirect()
            ->route('admin.publications.index')
            ->with('success', 'Publication mise à jour.');
    }

    public function destroy(Publication $publication): RedirectResponse
    {
        $this->authorize('delete', $publication);

        foreach ($publication->blocks as $block) {
            if ($block->file_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($block->file_path);
            }
        }

        $publication->delete();
        Cache::flush();

        return redirect()
            ->route('admin.publications.index')
            ->with('success', 'Publication supprimée.');
    }

    private function savePublication(Publication $publication, StorePublicationRequest|UpdatePublicationRequest $request): Publication
    {
        $status = $request->input('status');
        $publishedAt = $this->resolvePublishedAt($request, $publication);

        $isNew = ! $publication->exists;
        $titleChanged = $publication->exists && $publication->title !== $request->input('title');

        $publication->fill([
            'user_id' => $request->user()->id,
            'title' => $request->input('title'),
            'slug' => ($isNew || $titleChanged || ! $publication->slug)
                ? $this->publicationService->generateUniqueSlug($request->input('title'), $publication->id)
                : $publication->slug,
            'status' => $status,
            'published_at' => $publishedAt,
        ]);
        $publication->save();

        $this->blockService->sync($publication, $this->blockService->blocksFromRequest($request));
        $this->publicationService->syncComputedFields($publication->fresh(['blocks']));

        $taggedUserIds = $request->input('tagged_users', []);
        $existingMentions = $publication->mentions()->pluck('mentioned_user_id')->toArray();
        $newTagIds = array_diff($taggedUserIds, $existingMentions);
        $removedTagIds = array_diff($existingMentions, $taggedUserIds);

        if (!empty($removedTagIds)) {
            $publication->mentions()->whereIn('mentioned_user_id', $removedTagIds)->delete();
        }

        foreach ($newTagIds as $userId) {
            $publication->mentions()->create(['mentioned_user_id' => $userId]);
            $user = \App\Models\User::find($userId);
            if ($user) {
                $user->notify(new \App\Notifications\PublicationTagNotification($publication));
            }
        }

        Cache::flush();

        return $publication;
    }

    private function resolvePublishedAt(StorePublicationRequest|UpdatePublicationRequest $request, Publication $publication): ?\Carbon\Carbon
    {
        if ($request->input('status') === Publication::STATUS_DRAFT) {
            return null;
        }

        if (! $request->filled('published_at')) {
            return now();
        }

        $date = \Carbon\Carbon::parse($request->input('published_at'))->startOfDay();

        if ($date->isFuture()) {
            return $date;
        }

        if ($date->isToday()) {
            return now();
        }

        return $date;
    }
}
