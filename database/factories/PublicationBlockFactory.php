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
        $faker = \Faker\Factory::create('fr_FR');
        return [
            'publication_id' => Publication::factory(),
            'type' => PublicationBlock::TYPE_TEXT,
            'content' => ['text' => $faker->realText(800)],
            'file_path' => null,
            'sort_order' => $faker->numberBetween(0, 100),
        ];
    }

    public function image(): static
    {
        $faker = \Faker\Factory::create('fr_FR');
        return $this->state(fn(array $attributes) => [
            'type' => PublicationBlock::TYPE_IMAGE,
            'content' => ['alt' => $faker->sentence(), 'caption' => $faker->sentence()],
            'file_path' => null,
        ]);
    }
}
