<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lesson;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('admin/index', [
            'stats' => [
                'courses' => Course::count(),
                'chapters' => Chapter::count(),
                'lessons' => Lesson::count(),
            ],
            'recentCourses' => Course::query()
                ->withCount(['chapters', 'chapters as lessons_count' => function ($query) {
                    $query->withCount('lessons');
                }])
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }
}
