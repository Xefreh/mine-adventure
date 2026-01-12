<?php

use App\Models\Chapter;
use App\Models\Course;
use App\Models\CourseFaq;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use App\Models\User;

test('guests cannot access courses index', function () {
    $this->get('/courses')
        ->assertRedirect('/login');
});

test('authenticated users can view courses index', function () {
    $user = User::factory()->create();
    Course::factory()->count(3)->create();

    $this->actingAs($user)
        ->get('/courses')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('courses/index')
            ->has('courses', 3)
        );
});

test('courses index shows completed lesson ids for authenticated user', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();
    $lesson1 = Lesson::factory()->for($chapter)->create();
    $lesson2 = Lesson::factory()->for($chapter)->create();

    LessonCompletion::factory()->create([
        'user_id' => $user->id,
        'lesson_id' => $lesson1->id,
    ]);

    $this->actingAs($user)
        ->get('/courses')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('courses/index')
            ->has('courses', 1)
            ->has('completedLessonIds', 1)
            ->where('completedLessonIds.0', $lesson1->id)
        );
});

test('guests cannot access course show page', function () {
    $course = Course::factory()->create();

    $this->get("/courses/{$course->id}")
        ->assertRedirect('/login');
});

test('authenticated users can view course show page', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create(['description' => 'Test description']);

    $this->actingAs($user)
        ->get("/courses/{$course->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('courses/show')
            ->has('course')
            ->where('course.description', 'Test description')
        );
});

test('course show page includes chapters with lessons', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();
    Lesson::factory()->count(3)->for($chapter)->create();

    $this->actingAs($user)
        ->get("/courses/{$course->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('courses/show')
            ->has('course.chapters', 1)
            ->has('course.chapters.0.lessons', 3)
        );
});

test('course show page includes faqs', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    CourseFaq::factory()->count(2)->for($course)->create();

    $this->actingAs($user)
        ->get("/courses/{$course->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('courses/show')
            ->has('course.faqs', 2)
        );
});

test('course show page includes progress information', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();
    $lesson1 = Lesson::factory()->for($chapter)->create();
    $lesson2 = Lesson::factory()->for($chapter)->create();

    LessonCompletion::factory()->create([
        'user_id' => $user->id,
        'lesson_id' => $lesson1->id,
    ]);

    $this->actingAs($user)
        ->get("/courses/{$course->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('courses/show')
            ->where('progressPercentage', 50)
            ->has('completedLessonIds', 1)
        );
});

test('course show page includes next lesson for user', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create(['position' => 1]);
    $lesson1 = Lesson::factory()->for($chapter)->create();
    $lesson2 = Lesson::factory()->for($chapter)->create();

    LessonCompletion::factory()->create([
        'user_id' => $user->id,
        'lesson_id' => $lesson1->id,
    ]);

    $this->actingAs($user)
        ->get("/courses/{$course->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('courses/show')
            ->where('nextLesson.id', $lesson2->id)
        );
});

test('course show page returns first lesson when no progress', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create(['position' => 1]);
    $lesson = Lesson::factory()->for($chapter)->create();

    $this->actingAs($user)
        ->get("/courses/{$course->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('courses/show')
            ->where('nextLesson.id', $lesson->id)
        );
});
