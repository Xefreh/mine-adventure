<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class LessonController extends Controller
{
    public function show(Course $course, Lesson $lesson): Response
    {
        $lesson->load([
            'blocks' => fn ($query) => $query->orderBy('position'),
            'blocks.video',
            'blocks.text',
            'blocks.resource',
            'blocks.assignment',
            'blocks.quiz',
            'blocks.quiz.questions',
            'chapter',
        ]);

        $course->load([
            'chapters' => fn ($query) => $query->orderBy('position'),
            'chapters.lessons',
        ]);

        $user = Auth::user();
        $completedLessonIds = $user->lessonCompletions()
            ->whereHas('lesson.chapter', fn ($query) => $query->where('course_id', $course->id))
            ->pluck('lesson_id')
            ->toArray();

        $allLessons = $course->chapters->flatMap->lessons;
        $currentIndex = $allLessons->search(fn ($l) => $l->id === $lesson->id);

        $prevLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;

        $totalLessons = $allLessons->count();
        $currentLessonNumber = $currentIndex + 1;

        return Inertia::render('lessons/show', [
            'course' => $course,
            'lesson' => $lesson,
            'chapters' => $course->chapters,
            'completedLessonIds' => $completedLessonIds,
            'prevLesson' => $prevLesson,
            'nextLesson' => $nextLesson,
            'currentLessonNumber' => $currentLessonNumber,
            'totalLessons' => $totalLessons,
        ]);
    }

    public function complete(Course $course, Lesson $lesson): RedirectResponse
    {
        $user = Auth::user();

        LessonCompletion::firstOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'completed_at' => now(),
            ]
        );

        return back()->with('success', 'Lesson marked as complete!');
    }
}
