<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentReportRequest;
use App\Models\Comment;
use App\Models\CommentReport;
use Illuminate\Http\RedirectResponse;

class CommentReportController extends Controller
{
    public function store(StoreCommentReportRequest $request, Comment $comment): RedirectResponse
    {
        $exists = CommentReport::where('comment_id', $comment->id)
            ->where('reporter_id', $request->user()->id)
            ->where('status', CommentReport::STATUS_PENDING)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Vous avez déjà signalé ce commentaire.');
        }

        CommentReport::create([
            'comment_id' => $comment->id,
            'reporter_id' => $request->user()->id,
            'reason' => $request->input('reason'),
            'status' => CommentReport::STATUS_PENDING,
        ]);

        return back()->with('success', 'Commentaire signalé. Merci.');
    }
}
