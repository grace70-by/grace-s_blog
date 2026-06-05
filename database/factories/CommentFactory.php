<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'publication_id' => Publication::factory(),
            'user_id' => User::factory(),
            'parent_id' => null,
            'body' => fake()->realText(200),
            'hidden_at' => null,
            'edited_at' => fake()->boolean(10) ? fake()->dateTimeBetween('-1 month', 'now') : null,
        ];
    }
}
