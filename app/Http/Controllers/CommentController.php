<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Publication;
use App\Services\CommentService;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function __construct(
        private CommentService $commentService
    ) {}

    public function store(StoreCommentRequest $request, Publication $publication): RedirectResponse
    {
        $this->authorize('create', Comment::class);

        if (! $publication->isPublished()) {
            abort(404);
        }

        $this->commentService->create([
            'publication_id' => $publication->id,
            'body' => $request->input('body'),
        ], $request->user());

        return back()->with('success', 'Commentaire publié.');
    }

    public function reply(StoreCommentRequest $request, Comment $comment): RedirectResponse
    {
        $this->authorize('create', Comment::class);

        if (! $comment->publication->isPublished()) {
            abort(404);
        }

        $this->commentService->create([
            'publication_id' => $comment->publication_id,
            'parent_id' => $comment->id,
            'body' => $request->input('body'),
        ], $request->user());

        return back()->with('success', 'Réponse publiée.');
    }

    public function update(UpdateCommentRequest $request, Comment $comment): RedirectResponse
    {
        $this->authorize('update', $comment);

        $this->commentService->update($comment, $request->input('body'));

        return back()->with('success', 'Commentaire modifié.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return back()->with('success', 'Commentaire supprimé.');
    }
}
