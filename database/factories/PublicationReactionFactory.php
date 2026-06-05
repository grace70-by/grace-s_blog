<?php

namespace Database\Factories;

use App\Models\PublicationReaction;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PublicationReactionFactory extends Factory
{
    protected $model = PublicationReaction::class;

    public function definition(): array
    {
        return [
            'publication_id' => Publication::factory(),
            'user_id' => User::factory(),
            'type' => 'like',
        ];
    }
}
