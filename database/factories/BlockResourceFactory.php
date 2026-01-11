<?php

namespace Database\Factories;

use App\Models\LessonBlock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlockResource>
 */
class BlockResourceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'block_id' => LessonBlock::factory()->resources(),
            'links' => [
                ['title' => fake()->sentence(3), 'url' => fake()->url()],
                ['title' => fake()->sentence(3), 'url' => fake()->url()],
            ],
        ];
    }
}
