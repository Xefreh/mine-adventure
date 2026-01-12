<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\LessonCompletion;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $user = Auth::user();
        $courses = Course::withCount('chapters')->orderBy('id')->get();

        // Get all user's lesson completions with related data
        $completions = $user->lessonCompletions()
            ->with('lesson.chapter.course')
            ->orderBy('completed_at', 'desc')
            ->get();

        $completedLessonIds = $completions->pluck('lesson_id')->toArray();

        // Calculate stats
        $stats = $this->calculateStats($user, $completions, $courses);

        // Get current course progress (most recently active course)
        $currentCourseProgress = $this->getCurrentCourseProgress($completions, $completedLessonIds, $courses);

        // Get next lesson for current course
        $nextLesson = null;
        if ($currentCourseProgress) {
            $nextLesson = $this->getNextLesson($currentCourseProgress['course'], $completedLessonIds);
        }

        return Inertia::render('dashboard', [
            'courses' => $courses,
            'currentCourseProgress' => $currentCourseProgress,
            'nextLesson' => $nextLesson,
            'stats' => $stats,
        ]);
    }

    /**
     * Calculate user dashboard stats.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<int, LessonCompletion>  $completions
     * @param  \Illuminate\Database\Eloquent\Collection<int, Course>  $courses
     * @return array{totalCoursesStarted: int, totalCoursesCompleted: int, totalLessonsCompleted: int, currentStreak: int, lastActivityAt: string|null}
     */
    private function calculateStats($user, $completions, $courses): array
    {
        $coursesWithProgress = $completions
            ->map(fn ($c) => $c->lesson?->chapter?->course_id)
            ->filter()
            ->unique()
            ->count();

        // Calculate completed courses
        $completedCourses = 0;
        foreach ($courses as $course) {
            $course->load('chapters.lessons');
            $totalLessons = $course->chapters->sum(fn ($chapter) => $chapter->lessons->count());
            if ($totalLessons > 0) {
                $completedInCourse = $completions
                    ->filter(fn ($c) => $c->lesson?->chapter?->course_id === $course->id)
                    ->count();
                if ($completedInCourse >= $totalLessons) {
                    $completedCourses++;
                }
            }
        }

        // Calculate current streak (consecutive days with activity)
        $streak = $this->calculateStreak($completions);

        $lastActivity = $completions->first();

        return [
            'totalCoursesStarted' => $coursesWithProgress,
            'totalCoursesCompleted' => $completedCourses,
            'totalLessonsCompleted' => $completions->count(),
            'currentStreak' => $streak,
            'lastActivityAt' => $lastActivity?->completed_at?->toIso8601String(),
        ];
    }

    /**
     * Calculate current streak of consecutive days.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<int, LessonCompletion>  $completions
     */
    private function calculateStreak($completions): int
    {
        if ($completions->isEmpty()) {
            return 0;
        }

        $dates = $completions
            ->map(fn ($c) => $c->completed_at?->toDateString())
            ->filter()
            ->unique()
            ->sort()
            ->reverse()
            ->values();

        if ($dates->isEmpty()) {
            return 0;
        }

        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        // If the most recent activity is not today or yesterday, streak is broken
        if ($dates->first() !== $today && $dates->first() !== $yesterday) {
            return 0;
        }

        $streak = 1;
        $current = $dates->first();

        foreach ($dates->slice(1) as $date) {
            $expected = now()->parse($current)->subDay()->toDateString();
            if ($date === $expected) {
                $streak++;
                $current = $date;
            } else {
                break;
            }
        }

        return $streak;
    }

    /**
     * Get current course progress for the timeline.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<int, LessonCompletion>  $completions
     * @param  array<int>  $completedLessonIds
     * @param  \Illuminate\Database\Eloquent\Collection<int, Course>  $courses
     * @return array{course: Course, progressPercentage: int, completedLessons: int, totalLessons: int, chapters: array<int, array{id: int, name: string, totalLessons: int, lessonsCompleted: int, isComplete: bool, isCurrent: bool, isLocked: bool}>}|null
     */
    private function getCurrentCourseProgress($completions, array $completedLessonIds, $courses): ?array
    {
        // Find the most recently active course
        $recentCompletion = $completions->first();

        if (! $recentCompletion || ! $recentCompletion->lesson?->chapter?->course_id) {
            // No activity yet - suggest the first course
            $firstCourse = $courses->first();
            if (! $firstCourse) {
                return null;
            }

            return $this->buildCourseProgress($firstCourse, $completedLessonIds);
        }

        $currentCourse = $courses->firstWhere('id', $recentCompletion->lesson->chapter->course_id);

        if (! $currentCourse) {
            return null;
        }

        return $this->buildCourseProgress($currentCourse, $completedLessonIds);
    }

    /**
     * Build course progress data structure.
     *
     * @param  array<int>  $completedLessonIds
     * @return array{course: Course, progressPercentage: int, completedLessons: int, totalLessons: int, chapters: array<int, array{id: int, name: string, totalLessons: int, lessonsCompleted: int, isComplete: bool, isCurrent: bool, isLocked: bool, lessons: array<int, array{id: int, name: string, isComplete: bool, isCurrent: bool, isLocked: bool}>}>}
     */
    private function buildCourseProgress(Course $course, array $completedLessonIds): array
    {
        $course->load(['chapters' => fn ($q) => $q->orderBy('position'), 'chapters.lessons']);

        $totalLessons = $course->chapters->sum(fn ($chapter) => $chapter->lessons->count());
        $completedLessons = 0;
        $foundCurrentChapter = false;
        $foundCurrentLesson = false;
        $chapters = [];

        foreach ($course->chapters as $chapter) {
            $chapterLessons = $chapter->lessons->count();
            $chapterCompleted = $chapter->lessons->filter(fn ($l) => in_array($l->id, $completedLessonIds))->count();
            $completedLessons += $chapterCompleted;

            $isChapterComplete = $chapterCompleted >= $chapterLessons && $chapterLessons > 0;
            $isChapterCurrent = false;
            $isChapterLocked = $foundCurrentChapter;

            // Mark as current if it's the first incomplete chapter
            if (! $foundCurrentChapter && ! $isChapterComplete) {
                $isChapterCurrent = true;
                $foundCurrentChapter = true;
                $isChapterLocked = false;
            }

            // Build lesson progress for this chapter
            $lessonProgress = [];
            foreach ($chapter->lessons as $lesson) {
                $isLessonComplete = in_array($lesson->id, $completedLessonIds);
                $isLessonCurrent = false;
                $isLessonLocked = $isChapterLocked || $foundCurrentLesson;

                // Mark as current if it's the first incomplete lesson
                if (! $foundCurrentLesson && ! $isLessonComplete && ! $isChapterLocked) {
                    $isLessonCurrent = true;
                    $foundCurrentLesson = true;
                    $isLessonLocked = false;
                }

                $lessonProgress[] = [
                    'id' => $lesson->id,
                    'name' => $lesson->name,
                    'isComplete' => $isLessonComplete,
                    'isCurrent' => $isLessonCurrent,
                    'isLocked' => $isLessonLocked,
                ];
            }

            $chapters[] = [
                'id' => $chapter->id,
                'name' => $chapter->name,
                'totalLessons' => $chapterLessons,
                'lessonsCompleted' => $chapterCompleted,
                'isComplete' => $isChapterComplete,
                'isCurrent' => $isChapterCurrent,
                'isLocked' => $isChapterLocked,
                'lessons' => $lessonProgress,
            ];
        }

        $progressPercentage = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

        return [
            'course' => $course,
            'progressPercentage' => $progressPercentage,
            'completedLessons' => $completedLessons,
            'totalLessons' => $totalLessons,
            'chapters' => $chapters,
        ];
    }

    /**
     * Get the next incomplete lesson for a course.
     *
     * @param  array<int>  $completedLessonIds
     */
    private function getNextLesson(Course $course, array $completedLessonIds): ?\App\Models\Lesson
    {
        foreach ($course->chapters as $chapter) {
            foreach ($chapter->lessons as $lesson) {
                if (! in_array($lesson->id, $completedLessonIds)) {
                    return $lesson;
                }
            }
        }

        return null;
    }
}
