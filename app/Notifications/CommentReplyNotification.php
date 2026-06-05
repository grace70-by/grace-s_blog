<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentReplyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Comment $comment,
        public Comment $parentComment
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('publications.show', $this->comment->publication).'#comments';

        return (new MailMessage)
            ->subject('Nouvelle réponse à votre commentaire')
            ->greeting('Bonjour '.$notifiable->name.' !')
            ->line($this->comment->user->name.' a répondu à votre commentaire.')
            ->action('Voir la réponse', $url);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'reply',
            'comment_id' => $this->comment->id,
            'publication_id' => $this->comment->publication_id,
            'publication_title' => $this->comment->publication->title,
            'author_name' => $this->comment->user->name,
            'url' => route('publications.show', $this->comment->publication).'#comments',
        ];
    }
}
