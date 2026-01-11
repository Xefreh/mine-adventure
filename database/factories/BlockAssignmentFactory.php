<?php

namespace Database\Factories;

use App\Models\LessonBlock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlockAssignment>
 */
class BlockAssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'block_id' => LessonBlock::factory()->assignment(),
            'instructions' => fake()->paragraphs(2, true),
            'starter_code' => fake()->optional()->text(500),
        ];
    }
}
