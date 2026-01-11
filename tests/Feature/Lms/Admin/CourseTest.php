<?php

use App\Models\Course;
use App\Models\User;

test('non-admins cannot access admin dashboard', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});

test('admins can access admin dashboard', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('admin/index'));
});

test('non-admins cannot access course management', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get('/admin/courses')
        ->assertForbidden();
});

test('admins can view courses list', function () {
    $user = User::factory()->admin()->create();
    Course::factory()->count(3)->create();

    $this->actingAs($user)
        ->get('/admin/courses')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('admin/courses/index')
            ->has('courses', 3)
        );
});

test('admins can view create course page', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->get('/admin/courses/create')
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('admin/courses/create'));
});

test('admins can create a course', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->post('/admin/courses', [
            'name' => 'Test Course',
            'thumbnail' => 'https://example.com/image.jpg',
            'difficulty' => 'easy',
        ])
        ->assertRedirect('/admin/courses');

    $this->assertDatabaseHas('courses', [
        'name' => 'Test Course',
        'difficulty' => 'easy',
    ]);
});

test('course creation requires valid data', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->post('/admin/courses', [
            'name' => '',
            'difficulty' => 'invalid',
        ])
        ->assertSessionHasErrors(['name', 'difficulty']);
});

test('admins can view edit course page', function () {
    $user = User::factory()->admin()->create();
    $course = Course::factory()->create();

    $this->actingAs($user)
        ->get("/admin/courses/{$course->id}/edit")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('admin/courses/edit')
            ->has('course')
        );
});

test('admins can update a course', function () {
    $user = User::factory()->admin()->create();
    $course = Course::factory()->create(['name' => 'Old Name']);

    $this->actingAs($user)
        ->patch("/admin/courses/{$course->id}", [
            'name' => 'New Name',
            'thumbnail' => 'https://example.com/new.jpg',
            'difficulty' => 'hard',
        ])
        ->assertRedirect('/admin/courses');

    $this->assertDatabaseHas('courses', [
        'id' => $course->id,
        'name' => 'New Name',
        'difficulty' => 'hard',
    ]);
});

test('admins can delete a course', function () {
    $user = User::factory()->admin()->create();
    $course = Course::factory()->create();

    $this->actingAs($user)
        ->delete("/admin/courses/{$course->id}")
        ->assertRedirect('/admin/courses');

    $this->assertDatabaseMissing('courses', ['id' => $course->id]);
});

test('course name must be unique on create', function () {
    $user = User::factory()->admin()->create();
    Course::factory()->create(['name' => 'Existing Course']);

    $this->actingAs($user)
        ->post('/admin/courses', [
            'name' => 'Existing Course',
            'difficulty' => 'easy',
        ])
        ->assertSessionHasErrors(['name']);
});

test('course name must be unique on update except for self', function () {
    $user = User::factory()->admin()->create();
    $course1 = Course::factory()->create(['name' => 'Course One']);
    $course2 = Course::factory()->create(['name' => 'Course Two']);

    // Updating to existing name fails
    $this->actingAs($user)
        ->patch("/admin/courses/{$course2->id}", [
            'name' => 'Course One',
            'difficulty' => 'easy',
        ])
        ->assertSessionHasErrors(['name']);

    // Updating to same name works
    $this->actingAs($user)
        ->patch("/admin/courses/{$course1->id}", [
            'name' => 'Course One',
            'difficulty' => 'hard',
        ])
        ->assertSessionDoesntHaveErrors(['name']);
});
