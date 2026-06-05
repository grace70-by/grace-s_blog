<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicationMention extends Model
{
    protected $fillable = [
        'publication_id',
        'mentioned_user_id',
    ];

    public function publication()
    {
        return $this->belongsTo(Publication::class);
    }

    public function mentionedUser()
    {
        return $this->belongsTo(User::class, 'mentioned_user_id');
    }
}
