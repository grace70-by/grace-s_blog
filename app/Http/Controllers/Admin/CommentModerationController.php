<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentModerationController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->query('filter', 'all');

        $commentsQuery = Comment::withTrashed()
            ->with(['user', 'publication', 'reports'])
            ->latest();

        $commentsQuery = match ($filter) {
            'reported' => $commentsQuery->whereHas('reports', fn ($q) => $q->where('status', CommentReport::STATUS_PENDING)),
            'hidden' => $commentsQuery->whereNotNull('hidden_at'),
            default => $commentsQuery,
        };

        $comments = $commentsQuery->paginate(20)->withQueryString();

        $pendingReports = CommentReport::with(['comment.user', 'comment.publication', 'reporter'])
            ->where('status', CommentReport::STATUS_PENDING)
            ->latest()
            ->paginate(20, ['*'], 'reports_page');

        return view('admin.comments.index', compact('comments', 'pendingReports', 'filter'));
    }

    public function hide(Comment $comment): RedirectResponse
    {
        $this->authorize('hide', $comment);

        $comment->update(['hidden_at' => now()]);

        return back()->with('success', 'Commentaire masqué.');
    }

    public function unhide(Comment $comment): RedirectResponse
    {
        $this->authorize('hide', $comment);

        $comment->update(['hidden_at' => null]);

        return back()->with('success', 'Commentaire réaffiché.');
    }

    public function destroy(int $commentId): RedirectResponse
    {
        $comment = Comment::withTrashed()->findOrFail($commentId);

        $this->authorize('delete', $comment);

        $comment->forceDelete();

        return back()->with('success', 'Commentaire supprimé définitivement.');
    }

    public function updateReport(Request $request, CommentReport $report): RedirectResponse
    {
        $this->authorize('moderate', Comment::class);

        $request->validate([
            'status' => ['required', 'in:reviewed,dismissed'],
        ]);

        $report->update([
            'status' => $request->input('status'),
            'reviewed_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Signalement traité.');
    }
}
