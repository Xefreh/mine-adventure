<?php

namespace Database\Factories;

use App\Models\LessonBlock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlockText>
 */
class BlockTextFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'block_id' => LessonBlock::factory()->text(),
            'content' => fake()->paragraphs(3, true),
        ];
    }
}
