<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\PublicationReaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicationReactionController extends Controller
{
    public function toggle(Request $request, Publication $publication): JsonResponse
    {
        if (! $publication->isPublished()) {
            abort(404);
        }

        $user = $request->user();

        $existing = PublicationReaction::where('publication_id', $publication->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            PublicationReaction::create([
                'publication_id' => $publication->id,
                'user_id' => $user->id,
                'type' => 'like',
            ]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'count' => $publication->reactions()->count(),
        ]);
    }
}
