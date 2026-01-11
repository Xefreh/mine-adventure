<?php

namespace Database\Factories;

use App\Models\Chapter;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chapter>
 */
class ChapterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'course_id' => Course::factory(),
            'position' => function (array $attributes) {
                $maxPosition = Chapter::where('course_id', $attributes['course_id'])->max('position') ?? 0;

                return $maxPosition + 1;
            },
        ];
    }
}
