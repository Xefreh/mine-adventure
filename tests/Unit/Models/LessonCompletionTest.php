<?php

use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\User;

test('lesson completion belongs to user', function () {
    $user = User::factory()->create();
    $completion = LessonCompletion::factory()->create(['user_id' => $user->id]);

    expect($completion->user)->toBeInstanceOf(User::class);
    expect($completion->user->id)->toBe($user->id);
});

test('lesson completion belongs to lesson', function () {
    $lesson = Lesson::factory()->create();
    $completion = LessonCompletion::factory()->create(['lesson_id' => $lesson->id]);

    expect($completion->lesson)->toBeInstanceOf(Lesson::class);
    expect($completion->lesson->id)->toBe($lesson->id);
});

test('lesson completion has completed at timestamp', function () {
    $completion = LessonCompletion::factory()->create();

    expect($completion->completed_at)->toBeInstanceOf(DateTime::class);
});

test('user has lesson completions relationship', function () {
    $user = User::factory()->create();
    LessonCompletion::factory()->count(3)->create(['user_id' => $user->id]);

    expect($user->lessonCompletions)->toHaveCount(3);
});

test('lesson has completions relationship', function () {
    $lesson = Lesson::factory()->create();
    LessonCompletion::factory()->count(2)->create(['lesson_id' => $lesson->id]);

    expect($lesson->completions)->toHaveCount(2);
});

test('user can check if lesson is completed', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();
    $lesson1 = Lesson::factory()->for($chapter)->create();
    $lesson2 = Lesson::factory()->for($chapter)->create();

    LessonCompletion::factory()->create([
        'user_id' => $user->id,
        'lesson_id' => $lesson1->id,
    ]);

    expect($user->hasCompletedLesson($lesson1))->toBeTrue();
    expect($user->hasCompletedLesson($lesson2))->toBeFalse();
});

test('has completed lesson checks correct user', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $lesson = Lesson::factory()->create();

    LessonCompletion::factory()->create([
        'user_id' => $user1->id,
        'lesson_id' => $lesson->id,
    ]);

    expect($user1->hasCompletedLesson($lesson))->toBeTrue();
    expect($user2->hasCompletedLesson($lesson))->toBeFalse();
});

test('lesson completion user id and lesson id are unique together', function () {
    $user = User::factory()->create();
    $lesson = Lesson::factory()->create();

    LessonCompletion::factory()->create([
        'user_id' => $user->id,
        'lesson_id' => $lesson->id,
    ]);

    expect(fn () => LessonCompletion::factory()->create([
        'user_id' => $user->id,
        'lesson_id' => $lesson->id,
    ]))->toThrow(Illuminate\Database\QueryException::class);
});
