<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    public function index(): Response
    {
        $courses = Course::query()
            ->withCount(['chapters', 'chapters as lessons_count' => function ($query) {
                $query->selectRaw('sum((select count(*) from lessons where lessons.chapter_id = chapters.id))');
            }])
            ->orderBy('name')
            ->get();

        $user = Auth::user();
        $completedLessonIds = $user->lessonCompletions()->pluck('lesson_id')->toArray();

        return Inertia::render('courses/index', [
            'courses' => $courses,
            'completedLessonIds' => $completedLessonIds,
        ]);
    }

    public function show(Course $course): Response
    {
        $course->load([
            'faqs' => fn ($query) => $query->orderBy('order'),
            'chapters' => fn ($query) => $query->orderBy('position'),
            'chapters.lessons',
        ]);

        $user = Auth::user();
        $completedLessonIds = $user->lessonCompletions()
            ->whereHas('lesson.chapter', fn ($query) => $query->where('course_id', $course->id))
            ->pluck('lesson_id')
            ->toArray();

        $totalLessons = $course->chapters->sum(fn ($chapter) => $chapter->lessons->count());
        $completedLessons = count($completedLessonIds);
        $progressPercentage = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

        $nextLesson = null;
        if ($completedLessons < $totalLessons) {
            foreach ($course->chapters as $chapter) {
                foreach ($chapter->lessons as $lesson) {
                    if (! in_array($lesson->id, $completedLessonIds)) {
                        $nextLesson = $lesson;
                        break 2;
                    }
                }
            }
        }

        return Inertia::render('courses/show', [
            'course' => $course,
            'completedLessonIds' => $completedLessonIds,
            'progressPercentage' => $progressPercentage,
            'totalLessons' => $totalLessons,
            'completedLessons' => $completedLessons,
            'nextLesson' => $nextLesson,
        ]);
    }
}
