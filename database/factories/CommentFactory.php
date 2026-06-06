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
            'body' => $this->faker->realText(200),
            'hidden_at' => null,
            'edited_at' => $this->faker->boolean(10) ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
        ];
    }
}
