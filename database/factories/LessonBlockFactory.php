<?php

namespace Database\Factories;

use App\Enums\BlockType;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LessonBlock>
 */
class LessonBlockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lesson_id' => Lesson::factory(),
            'type' => fake()->randomElement(BlockType::cases()),
            'position' => fake()->numberBetween(1, 100),
            'side' => null,
        ];
    }

    public function video(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => BlockType::Video,
        ]);
    }

    public function text(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => BlockType::Text,
        ]);
    }

    public function resources(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => BlockType::Resources,
        ]);
    }

    public function assignment(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => BlockType::Assignment,
        ]);
    }

    public function quiz(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => BlockType::Quiz,
        ]);
    }
}
