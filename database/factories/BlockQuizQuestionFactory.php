<?php

namespace Database\Factories;

use App\Models\BlockQuiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlockQuizQuestion>
 */
class BlockQuizQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'block_quiz_id' => BlockQuiz::factory(),
            'question' => fake()->sentence().'?',
            'options' => [
                fake()->sentence(3),
                fake()->sentence(3),
                fake()->sentence(3),
                fake()->sentence(3),
            ],
            'correct_answer' => fake()->numberBetween(0, 3),
            'position' => fake()->numberBetween(1, 100),
        ];
    }
}
