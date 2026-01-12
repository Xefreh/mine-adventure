<?php

namespace Database\Factories;

use App\Enums\CourseDifficulty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(3, true),
            'thumbnail' => fake()->imageUrl(640, 480, 'education'),
            'description' => fake()->paragraphs(3, true),
            'difficulty' => fake()->randomElement(CourseDifficulty::cases()),
        ];
    }

    public function easy(): static
    {
        return $this->state(fn (array $attributes) => [
            'difficulty' => CourseDifficulty::Easy,
        ]);
    }

    public function medium(): static
    {
        return $this->state(fn (array $attributes) => [
            'difficulty' => CourseDifficulty::Medium,
        ]);
    }

    public function hard(): static
    {
        return $this->state(fn (array $attributes) => [
            'difficulty' => CourseDifficulty::Hard,
        ]);
    }
}
