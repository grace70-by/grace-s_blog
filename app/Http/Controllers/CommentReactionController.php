<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentReaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentReactionController extends Controller
{
    public function toggle(Request $request, Comment $comment): JsonResponse
    {
        $user = $request->user();

        $existing = CommentReaction::where('comment_id', $comment->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            CommentReaction::create([
                'comment_id' => $comment->id,
                'user_id' => $user->id,
                'type' => 'like',
            ]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'count' => $comment->reactions()->count(),
        ]);
    }
}
