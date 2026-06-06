<?php

namespace Database\Factories;

use App\Models\Publication;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PublicationFactory extends Factory
{
    protected $model = Publication::class;

    public function definition(): array
    {
        $faker = \Faker\Factory::create('fr_FR');
        $title = $faker->unique()->realText(60);
        return [
            'user_id' => User::factory(),
            'title' => rtrim($title, '.'),
            'slug' => Str::slug($title),
            'status' => Publication::STATUS_PUBLISHED,
            'meta_description' => $faker->realText(150),
            'published_at' => $faker->dateTimeBetween('-1 year', 'now'),
            'views_count' => $faker->numberBetween(10, 10000),
            'reading_time_minutes' => $faker->numberBetween(1, 15),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => Publication::STATUS_DRAFT,
            'published_at' => null,
        ]);
    }
}
