<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Services\CommentService;
use App\Services\PublicationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicationController extends Controller
{
    public function __construct(
        private CommentService $commentService,
        private PublicationService $publicationService
    ) {}

    public function index(Request $request): View
    {
        $publications = Publication::published()
            ->withLikeMeta()
            ->with(['author', 'blocks'])
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('publications.index', compact('publications'));
    }

    public function show(Request $request, Publication $publication): View
    {
        $this->authorize('view', $publication);

        $this->publicationService->incrementViews($publication);

        $publication->load(['author', 'blocks']);
        $publication->loadCount('reactions');

        if (auth()->check()) {
            $publication->loadExists(['reactions as user_has_liked' => fn ($q) => $q->where('user_id', auth()->id())]);
        }

        $sort = $request->query('sort', 'recent');
        $childEager = function ($query) use ($sort) {
            $this->applyCommentSort($query, $sort);
            $query->visible()
                ->withCount('reactions')
                ->with(['user', 'reactions', 'mentions.mentionedUser']);
        };

        $commentsQuery = $publication->comments()
            ->visible()
            ->topLevel()
            ->with([
                'user',
                'reactions',
                'mentions.mentionedUser',
                'children' => function ($query) use ($sort, $childEager) {
                    $childEager($query);
                    $query->with(['children' => $childEager]);
                },
            ])
            ->withCount('reactions');

        $this->applyCommentSort($commentsQuery, $sort);

        $comments = $commentsQuery->get();
        $related = $this->publicationService->related($publication);

        return view('publications.show', [
            'publication' => $publication,
            'comments' => $comments,
            'sort' => $sort,
            'commentService' => $this->commentService,
            'publicationService' => $this->publicationService,
            'related' => $related,
        ]);
    }

    private function applyCommentSort($query, string $sort): void
    {
        if ($sort === 'popular') {
            $query->orderByDesc('reactions_count')->orderByDesc('created_at');
        } else {
            $query->latest();
        }
    }
}
