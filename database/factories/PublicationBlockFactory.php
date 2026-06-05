<?php

namespace Database\Factories;

use App\Models\PublicationBlock;
use App\Models\Publication;
use Illuminate\Database\Eloquent\Factories\Factory;

class PublicationBlockFactory extends Factory
{
    protected $model = PublicationBlock::class;

    public function definition(): array
    {
        return [
            'publication_id' => Publication::factory(),
            'type' => PublicationBlock::TYPE_TEXT,
            'content' => ['text' => fake()->realText(800)],
            'file_path' => null,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }

    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => PublicationBlock::TYPE_IMAGE,
            'content' => ['alt' => fake()->sentence(), 'caption' => fake()->sentence()],
            'file_path' => null, 
        ]);
    }
}
