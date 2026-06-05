<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\CommentMention;
use App\Models\User;
use App\Notifications\CommentMentionNotification;
use App\Notifications\CommentReplyNotification;
use Illuminate\Support\Facades\DB;

class CommentService
{
    public function create(array $data, User $user): Comment
    {
        return DB::transaction(function () use ($data, $user) {
            $comment = Comment::create([
                'publication_id' => $data['publication_id'],
                'user_id' => $user->id,
                'parent_id' => $data['parent_id'] ?? null,
                'body' => $data['body'],
            ]);

            $this->syncMentions($comment, $data['body']);
            $this->notifyParticipants($comment);

            return $comment->load(['user', 'reactions', 'mentions.mentionedUser']);
        });
    }

    public function update(Comment $comment, string $body): Comment
    {
        return DB::transaction(function () use ($comment, $body) {
            $comment->update([
                'body' => $body,
                'edited_at' => now(),
            ]);

            $comment->mentions()->delete();
            $this->syncMentions($comment, $body);

            return $comment->fresh(['user', 'reactions', 'mentions.mentionedUser']);
        });
    }

    public function syncMentions(Comment $comment, string $body): void
    {
        preg_match_all('/\B@([a-zA-Z0-9_]+)\b/', $body, $matches);

        $usernames = array_unique($matches[1] ?? []);

        if ($usernames === []) {
            return;
        }

        $users = User::whereIn('username', $usernames)->get();

        foreach ($users as $mentionedUser) {
            CommentMention::firstOrCreate([
                'comment_id' => $comment->id,
                'mentioned_user_id' => $mentionedUser->id,
            ]);
        }
    }

    public function formatBodyWithMentions(Comment $comment): string
    {
        $body = e($comment->body);

        $comment->loadMissing('mentions.mentionedUser');

        foreach ($comment->mentions as $mention) {
            $username = e($mention->mentionedUser->username);
            $body = preg_replace(
                '/@'.preg_quote($username, '/').'\b/',
                '<a href="'.route('authors.show', $mention->mentionedUser).'" class="text-ig-pink font-semibold hover:underline">@'.$username.'</a>',
                $body
            );
        }

        return nl2br($body);
    }

    private function notifyParticipants(Comment $comment): void
    {
        $comment->load(['publication', 'user', 'parent.user', 'mentions.mentionedUser']);

        foreach ($comment->mentions as $mention) {
            if ($mention->mentioned_user_id !== $comment->user_id) {
                $mention->mentionedUser->notify(new CommentMentionNotification($comment));
            }
        }

        if ($comment->parent_id && $comment->parent?->user_id !== $comment->user_id) {
            $comment->parent->user->notify(new CommentReplyNotification($comment, $comment->parent));
        }
    }
}
