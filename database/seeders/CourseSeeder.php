<?php

namespace Database\Seeders;

use App\Enums\CourseDifficulty;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'name' => 'Introduction to PHP',
                'thumbnail' => 'https://picsum.photos/seed/php/640/480',
                'difficulty' => CourseDifficulty::Easy,
                'chapters' => [
                    [
                        'name' => 'Getting Started',
                        'lessons' => [
                            'What is PHP?',
                            'Installing PHP',
                            'Your First PHP Script',
                            'PHP Syntax Basics',
                        ],
                    ],
                    [
                        'name' => 'Variables and Data Types',
                        'lessons' => [
                            'Understanding Variables',
                            'Strings and Numbers',
                            'Arrays in PHP',
                            'Working with Booleans',
                        ],
                    ],
                    [
                        'name' => 'Control Structures',
                        'lessons' => [
                            'If Statements',
                            'Switch Statements',
                            'For and While Loops',
                            'Foreach Loops',
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Laravel Fundamentals',
                'thumbnail' => 'https://picsum.photos/seed/laravel/640/480',
                'difficulty' => CourseDifficulty::Medium,
                'chapters' => [
                    [
                        'name' => 'Laravel Basics',
                        'lessons' => [
                            'Introduction to Laravel',
                            'Installing Laravel',
                            'Directory Structure',
                            'Artisan CLI',
                        ],
                    ],
                    [
                        'name' => 'Routing and Controllers',
                        'lessons' => [
                            'Basic Routing',
                            'Route Parameters',
                            'Creating Controllers',
                            'Resource Controllers',
                        ],
                    ],
                    [
                        'name' => 'Eloquent ORM',
                        'lessons' => [
                            'Introduction to Eloquent',
                            'Defining Models',
                            'CRUD Operations',
                            'Relationships',
                        ],
                    ],
                    [
                        'name' => 'Blade Templates',
                        'lessons' => [
                            'Blade Syntax',
                            'Template Inheritance',
                            'Components',
                            'Directives',
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Advanced React Patterns',
                'thumbnail' => 'https://picsum.photos/seed/react/640/480',
                'difficulty' => CourseDifficulty::Hard,
                'chapters' => [
                    [
                        'name' => 'Component Patterns',
                        'lessons' => [
                            'Compound Components',
                            'Render Props',
                            'Higher-Order Components',
                            'Custom Hooks',
                        ],
                    ],
                    [
                        'name' => 'State Management',
                        'lessons' => [
                            'Context API Deep Dive',
                            'useReducer Patterns',
                            'State Machines with XState',
                            'Server State with React Query',
                        ],
                    ],
                    [
                        'name' => 'Performance Optimization',
                        'lessons' => [
                            'React.memo and useMemo',
                            'useCallback Best Practices',
                            'Code Splitting',
                            'Virtual Lists',
                        ],
                    ],
                ],
            ],
        ];

        foreach ($courses as $courseData) {
            $course = Course::create([
                'name' => $courseData['name'],
                'thumbnail' => $courseData['thumbnail'],
                'difficulty' => $courseData['difficulty'],
            ]);

            foreach ($courseData['chapters'] as $chapterIndex => $chapterData) {
                $chapter = Chapter::create([
                    'name' => $chapterData['name'],
                    'course_id' => $course->id,
                    'position' => $chapterIndex + 1,
                ]);

                foreach ($chapterData['lessons'] as $lessonName) {
                    Lesson::create([
                        'name' => $lessonName,
                        'chapter_id' => $chapter->id,
                    ]);
                }
            }
        }
    }
}
