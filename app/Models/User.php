<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'workos_id',
        'avatar',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'workos_id',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * @return HasMany<LessonCompletion, $this>
     */
    public function lessonCompletions(): HasMany
    {
        return $this->hasMany(LessonCompletion::class);
    }

    public function hasCompletedLesson(Lesson $lesson): bool
    {
        return $this->lessonCompletions()->where('lesson_id', $lesson->id)->exists();
    }

    /**
     * Check if a lesson is accessible for this user.
     * A lesson is accessible if it's the first lesson or the previous lesson is completed.
     */
    public function canAccessLesson(Lesson $lesson): bool
    {
        $lesson->loadMissing('chapter.course.chapters.lessons');
        $course = $lesson->chapter->course;

        // Get all lessons in order (by chapter position, then lesson id)
        $orderedLessons = $course->chapters
            ->sortBy('position')
            ->flatMap(fn (Chapter $chapter) => $chapter->lessons->sortBy('id'))
            ->values();

        $lessonIndex = $orderedLessons->search(fn (Lesson $l) => $l->id === $lesson->id);

        // First lesson is always accessible
        if ($lessonIndex === 0) {
            return true;
        }

        // Check if the previous lesson is completed
        $previousLesson = $orderedLessons->get($lessonIndex - 1);

        return $previousLesson && $this->hasCompletedLesson($previousLesson);
    }

    /**
     * Get all accessible lesson IDs for a course.
     *
     * @return array<int>
     */
    public function getAccessibleLessonIds(Course $course): array
    {
        $course->loadMissing('chapters.lessons');

        $orderedLessons = $course->chapters
            ->sortBy('position')
            ->flatMap(fn (Chapter $chapter) => $chapter->lessons->sortBy('id'))
            ->values();

        $completedLessonIds = $this->lessonCompletions()
            ->whereIn('lesson_id', $orderedLessons->pluck('id'))
            ->pluck('lesson_id')
            ->toArray();

        $accessibleIds = [];

        foreach ($orderedLessons as $index => $lesson) {
            // First lesson is always accessible
            if ($index === 0) {
                $accessibleIds[] = $lesson->id;

                continue;
            }

            // If previous lesson is completed, this one is accessible
            $previousLesson = $orderedLessons->get($index - 1);
            if ($previousLesson && in_array($previousLesson->id, $completedLessonIds, true)) {
                $accessibleIds[] = $lesson->id;
            }
        }

        return $accessibleIds;
    }
}
