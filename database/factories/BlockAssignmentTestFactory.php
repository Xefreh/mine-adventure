<?php

namespace Database\Factories;

use App\Models\BlockAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlockAssignmentTest>
 */
class BlockAssignmentTestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'block_assignment_id' => BlockAssignment::factory(),
            'file_content' => fake()->text(1000),
            'class_name' => 'Test'.fake()->word().'Test',
        ];
    }
}
