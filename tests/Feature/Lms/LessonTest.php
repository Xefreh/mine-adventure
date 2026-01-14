<?php

use App\Models\BlockText;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonBlock;
use App\Models\LessonCompletion;
use App\Models\User;

test('guests cannot access lesson page', function () {
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();
    $lesson = Lesson::factory()->for($chapter)->create();

    $this->get("/courses/{$course->id}/lessons/{$lesson->id}")
        ->assertRedirect('/login');
});

test('authenticated users can view lesson page', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();
    $lesson = Lesson::factory()->for($chapter)->create();

    $this->actingAs($user)
        ->get("/courses/{$course->id}/lessons/{$lesson->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('lessons/show')
            ->has('course')
            ->has('lesson')
            ->has('lesson.chapter')
        );
});

test('lesson page includes blocks with blockable data', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();
    $lesson = Lesson::factory()->for($chapter)->create();

    $lessonBlock = LessonBlock::factory()->for($lesson)->create([
        'type' => App\Enums\BlockType::Text,
        'position' => 1,
    ]);
    BlockText::factory()->create([
        'block_id' => $lessonBlock->id,
        'content' => 'Test content',
    ]);

    $this->actingAs($user)
        ->get("/courses/{$course->id}/lessons/{$lesson->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('lessons/show')
            ->has('lesson.blocks', 1)
            ->where('lesson.blocks.0.text.content', 'Test content')
        );
});

test('lesson page includes navigation data', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();
    $lesson = Lesson::factory()->for($chapter)->create();

    $this->actingAs($user)
        ->get("/courses/{$course->id}/lessons/{$lesson->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('lessons/show')
            ->has('chapters')
            ->has('completedLessonIds')
        );
});

test('lesson page includes previous and next lesson', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();
    $lesson1 = Lesson::factory()->for($chapter)->create();
    $lesson2 = Lesson::factory()->for($chapter)->create();
    $lesson3 = Lesson::factory()->for($chapter)->create();

    // Complete lesson1 and lesson2 to access lesson2 and see lesson3 as next
    LessonCompletion::factory()->for($user)->for($lesson1)->create();
    LessonCompletion::factory()->for($user)->for($lesson2)->create();

    $this->actingAs($user)
        ->get("/courses/{$course->id}/lessons/{$lesson2->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('lessons/show')
            ->where('prevLesson.id', $lesson1->id)
            ->where('nextLesson.id', $lesson3->id)
        );
});

test('first lesson has no previous lesson', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();
    $lesson1 = Lesson::factory()->for($chapter)->create();
    $lesson2 = Lesson::factory()->for($chapter)->create();

    // Complete lesson1 to make lesson2 accessible as nextLesson
    LessonCompletion::factory()->for($user)->for($lesson1)->create();

    $this->actingAs($user)
        ->get("/courses/{$course->id}/lessons/{$lesson1->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('lessons/show')
            ->where('prevLesson', null)
            ->where('nextLesson.id', $lesson2->id)
        );
});

test('last lesson has no next lesson', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();
    $lesson1 = Lesson::factory()->for($chapter)->create();
    $lesson2 = Lesson::factory()->for($chapter)->create();

    // Complete lesson1 to access lesson2
    LessonCompletion::factory()->for($user)->for($lesson1)->create();

    $this->actingAs($user)
        ->get("/courses/{$course->id}/lessons/{$lesson2->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('lessons/show')
            ->where('prevLesson.id', $lesson1->id)
            ->where('nextLesson', null)
        );
});

test('lesson cannot be accessed from wrong course', function () {
    $user = User::factory()->create();
    $course1 = Course::factory()->create();
    $course2 = Course::factory()->create();
    $chapter = Chapter::factory()->for($course1)->create();
    $lesson = Lesson::factory()->for($chapter)->create();

    $this->actingAs($user)
        ->get("/courses/{$course2->id}/lessons/{$lesson->id}")
        ->assertNotFound();
});

test('guests cannot mark lesson as complete', function () {
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();
    $lesson = Lesson::factory()->for($chapter)->create();

    $this->post("/courses/{$course->id}/lessons/{$lesson->id}/complete")
        ->assertRedirect('/login');
});

test('authenticated users can mark lesson as complete', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();
    $lesson = Lesson::factory()->for($chapter)->create();

    $this->actingAs($user)
        ->post("/courses/{$course->id}/lessons/{$lesson->id}/complete")
        ->assertRedirect();

    $this->assertDatabaseHas('lesson_completions', [
        'user_id' => $user->id,
        'lesson_id' => $lesson->id,
    ]);
});

test('marking lesson complete is idempotent', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();
    $lesson = Lesson::factory()->for($chapter)->create();

    LessonCompletion::factory()->create([
        'user_id' => $user->id,
        'lesson_id' => $lesson->id,
    ]);

    $this->actingAs($user)
        ->post("/courses/{$course->id}/lessons/{$lesson->id}/complete")
        ->assertRedirect();

    expect(LessonCompletion::where('user_id', $user->id)->where('lesson_id', $lesson->id)->count())->toBe(1);
});

test('lesson completion is scoped to user', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();
    $lesson = Lesson::factory()->for($chapter)->create();

    LessonCompletion::factory()->create([
        'user_id' => $user1->id,
        'lesson_id' => $lesson->id,
    ]);

    $this->actingAs($user2)
        ->post("/courses/{$course->id}/lessons/{$lesson->id}/complete")
        ->assertRedirect();

    expect(LessonCompletion::where('lesson_id', $lesson->id)->count())->toBe(2);
});

test('lesson show page reflects completion status', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();
    $lesson = Lesson::factory()->for($chapter)->create();

    LessonCompletion::factory()->create([
        'user_id' => $user->id,
        'lesson_id' => $lesson->id,
    ]);

    $this->actingAs($user)
        ->get("/courses/{$course->id}/lessons/{$lesson->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('lessons/show')
            ->has('completedLessonIds', 1)
            ->where('completedLessonIds.0', $lesson->id)
        );
});

// Lesson Locking Tests

test('first lesson is always accessible', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();
    $lesson = Lesson::factory()->for($chapter)->create();

    $this->actingAs($user)
        ->get("/courses/{$course->id}/lessons/{$lesson->id}")
        ->assertOk();
});

test('locked lesson redirects to course page', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();
    $lesson1 = Lesson::factory()->for($chapter)->create();
    $lesson2 = Lesson::factory()->for($chapter)->create();

    // Try to access lesson2 without completing lesson1
    $this->actingAs($user)
        ->get("/courses/{$course->id}/lessons/{$lesson2->id}")
        ->assertRedirect("/courses/{$course->id}");
});

test('lesson becomes accessible after previous is completed', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();
    $lesson1 = Lesson::factory()->for($chapter)->create();
    $lesson2 = Lesson::factory()->for($chapter)->create();

    // Complete lesson1
    LessonCompletion::factory()->for($user)->for($lesson1)->create();

    // Now lesson2 should be accessible
    $this->actingAs($user)
        ->get("/courses/{$course->id}/lessons/{$lesson2->id}")
        ->assertOk();
});

test('lesson page includes accessible lesson ids', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();
    $lesson1 = Lesson::factory()->for($chapter)->create();
    $lesson2 = Lesson::factory()->for($chapter)->create();
    $lesson3 = Lesson::factory()->for($chapter)->create();

    // Complete lesson1
    LessonCompletion::factory()->for($user)->for($lesson1)->create();

    $this->actingAs($user)
        ->get("/courses/{$course->id}/lessons/{$lesson1->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('lessons/show')
            ->has('accessibleLessonIds', 2)
            ->where('accessibleLessonIds.0', $lesson1->id)
            ->where('accessibleLessonIds.1', $lesson2->id)
        );
});

test('course page includes accessible lesson ids', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();
    $lesson1 = Lesson::factory()->for($chapter)->create();
    $lesson2 = Lesson::factory()->for($chapter)->create();

    $this->actingAs($user)
        ->get("/courses/{$course->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('courses/show')
            ->has('accessibleLessonIds', 1)
            ->where('accessibleLessonIds.0', $lesson1->id)
        );
});
