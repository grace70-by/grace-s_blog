<?php

namespace App\Notifications;

use App\Models\Publication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PublicationTagNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Publication $publication
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('publications.show', $this->publication);

        return (new MailMessage)
            ->subject('Vous avez été identifié sur '.config('app.name'))
            ->greeting('Bonjour '.$notifiable->name.' !')
            ->line($this->publication->author->name.' vous a identifié dans un article.')
            ->action('Voir l\'article', $url)
            ->line('Merci de participer à la communauté !');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'publication_tag',
            'publication_id' => $this->publication->id,
            'publication_title' => $this->publication->title,
            'author_name' => $this->publication->author->name,
            'url' => route('publications.show', $this->publication),
        ];
    }
}
