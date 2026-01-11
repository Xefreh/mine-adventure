<?php

use App\Models\Chapter;
use App\Models\Course;
use App\Models\User;

test('non-admins cannot access chapter management', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $course = Course::factory()->create();

    $this->actingAs($user)
        ->get("/admin/courses/{$course->id}/chapters")
        ->assertForbidden();
});

test('admins can view chapters list for a course', function () {
    $user = User::factory()->admin()->create();
    $course = Course::factory()->create();
    Chapter::factory()
        ->count(3)
        ->for($course)
        ->sequence(
            ['position' => 1],
            ['position' => 2],
            ['position' => 3],
        )
        ->create();

    $this->actingAs($user)
        ->get("/admin/courses/{$course->id}/chapters")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('admin/chapters/index')
            ->has('course')
            ->has('chapters', 3)
        );
});

test('admins can create a chapter', function () {
    $user = User::factory()->admin()->create();
    $course = Course::factory()->create();

    $this->actingAs($user)
        ->post("/admin/courses/{$course->id}/chapters", [
            'name' => 'New Chapter',
            'position' => 1,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('chapters', [
        'course_id' => $course->id,
        'name' => 'New Chapter',
        'position' => 1,
    ]);
});

test('chapter creation requires valid data', function () {
    $user = User::factory()->admin()->create();
    $course = Course::factory()->create();

    $this->actingAs($user)
        ->post("/admin/courses/{$course->id}/chapters", [
            'name' => '',
        ])
        ->assertSessionHasErrors(['name']);
});

test('admins can update a chapter', function () {
    $user = User::factory()->admin()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create(['name' => 'Old Name']);

    $this->actingAs($user)
        ->patch("/admin/courses/{$course->id}/chapters/{$chapter->id}", [
            'name' => 'New Name',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('chapters', [
        'id' => $chapter->id,
        'name' => 'New Name',
    ]);
});

test('admins can delete a chapter', function () {
    $user = User::factory()->admin()->create();
    $course = Course::factory()->create();
    $chapter = Chapter::factory()->for($course)->create();

    $this->actingAs($user)
        ->delete("/admin/courses/{$course->id}/chapters/{$chapter->id}")
        ->assertRedirect();

    $this->assertDatabaseMissing('chapters', ['id' => $chapter->id]);
});

test('admins can reorder chapters', function () {
    $user = User::factory()->admin()->create();
    $course = Course::factory()->create();
    $chapter1 = Chapter::factory()->for($course)->create(['position' => 1]);
    $chapter2 = Chapter::factory()->for($course)->create(['position' => 2]);

    $this->actingAs($user)
        ->post("/admin/courses/{$course->id}/chapters/reorder", [
            'chapters' => [
                ['id' => $chapter1->id, 'position' => 2],
                ['id' => $chapter2->id, 'position' => 1],
            ],
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('chapters', ['id' => $chapter1->id, 'position' => 2]);
    $this->assertDatabaseHas('chapters', ['id' => $chapter2->id, 'position' => 1]);
});

test('chapter position must be unique within a course', function () {
    $user = User::factory()->admin()->create();
    $course = Course::factory()->create();
    Chapter::factory()->for($course)->create(['position' => 1]);

    $this->actingAs($user)
        ->post("/admin/courses/{$course->id}/chapters", [
            'name' => 'Another Chapter',
            'position' => 1,
        ])
        ->assertSessionHasErrors(['position']);
});
