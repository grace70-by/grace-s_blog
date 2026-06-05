<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentMentionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Comment $comment
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('publications.show', $this->comment->publication).'#comments';

        return (new MailMessage)
            ->subject('Vous avez été mentionné sur '.config('app.name'))
            ->greeting('Bonjour '.$notifiable->name.' !')
            ->line($this->comment->user->name.' vous a mentionné dans un commentaire.')
            ->action('Voir le commentaire', $url)
            ->line('Merci de participer à la communauté !');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'mention',
            'comment_id' => $this->comment->id,
            'publication_id' => $this->comment->publication_id,
            'publication_title' => $this->comment->publication->title,
            'author_name' => $this->comment->user->name,
            'url' => route('publications.show', $this->comment->publication).'#comments',
        ];
    }
}
