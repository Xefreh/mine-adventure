<?php

namespace Database\Factories;

use App\Models\LessonBlock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlockVideo>
 */
class BlockVideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'block_id' => LessonBlock::factory()->video(),
            'url' => fake()->url(),
            'duration' => fake()->optional()->numberBetween(60, 3600),
        ];
    }
}
