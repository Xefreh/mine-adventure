<?php

use App\Models\Chapter;
use App\Models\Lesson;
use App\Models\User;

test('non-admins cannot access lesson management', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $chapter = Chapter::factory()->create();

    $this->actingAs($user)
        ->get("/admin/chapters/{$chapter->id}/lessons")
        ->assertForbidden();
});

test('admins can view lessons list for a chapter', function () {
    $user = User::factory()->admin()->create();
    $chapter = Chapter::factory()->create();
    Lesson::factory()->count(3)->for($chapter)->create();

    $this->actingAs($user)
        ->get("/admin/chapters/{$chapter->id}/lessons")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('admin/lessons/index')
            ->has('chapter')
            ->has('lessons', 3)
        );
});

test('admins can view create lesson page', function () {
    $user = User::factory()->admin()->create();
    $chapter = Chapter::factory()->create();

    $this->actingAs($user)
        ->get("/admin/chapters/{$chapter->id}/lessons/create")
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('admin/lessons/create'));
});

test('admins can create a lesson', function () {
    $user = User::factory()->admin()->create();
    $chapter = Chapter::factory()->create();

    $this->actingAs($user)
        ->post("/admin/chapters/{$chapter->id}/lessons", [
            'name' => 'New Lesson',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('lessons', [
        'chapter_id' => $chapter->id,
        'name' => 'New Lesson',
    ]);
});

test('lesson creation requires valid data', function () {
    $user = User::factory()->admin()->create();
    $chapter = Chapter::factory()->create();

    $this->actingAs($user)
        ->post("/admin/chapters/{$chapter->id}/lessons", [
            'name' => '',
        ])
        ->assertSessionHasErrors(['name']);
});

test('admins can view edit lesson page', function () {
    $user = User::factory()->admin()->create();
    $chapter = Chapter::factory()->create();
    $lesson = Lesson::factory()->for($chapter)->create();

    $this->actingAs($user)
        ->get("/admin/chapters/{$chapter->id}/lessons/{$lesson->id}")
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('admin/lessons/edit')
            ->has('lesson')
        );
});

test('admins can update a lesson', function () {
    $user = User::factory()->admin()->create();
    $chapter = Chapter::factory()->create();
    $lesson = Lesson::factory()->for($chapter)->create(['name' => 'Old Name']);

    $this->actingAs($user)
        ->patch("/admin/chapters/{$chapter->id}/lessons/{$lesson->id}", [
            'name' => 'New Name',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('lessons', [
        'id' => $lesson->id,
        'name' => 'New Name',
    ]);
});

test('admins can delete a lesson', function () {
    $user = User::factory()->admin()->create();
    $chapter = Chapter::factory()->create();
    $lesson = Lesson::factory()->for($chapter)->create();

    $this->actingAs($user)
        ->delete("/admin/chapters/{$chapter->id}/lessons/{$lesson->id}")
        ->assertRedirect();

    $this->assertDatabaseMissing('lessons', ['id' => $lesson->id]);
});

test('lesson name must be unique', function () {
    $user = User::factory()->admin()->create();
    $chapter = Chapter::factory()->create();
    Lesson::factory()->create(['name' => 'Existing Lesson']);

    $this->actingAs($user)
        ->post("/admin/chapters/{$chapter->id}/lessons", [
            'name' => 'Existing Lesson',
        ])
        ->assertSessionHasErrors(['name']);
});
