<?php

use App\Models\Course;
use App\Models\CourseFaq;
use App\Models\User;

test('non-admins cannot create faqs', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $course = Course::factory()->create();

    $this->actingAs($user)
        ->post("/admin/courses/{$course->id}/faqs", [
            'question' => 'Test question?',
            'answer' => 'Test answer.',
            'order' => 0,
        ])
        ->assertForbidden();
});

test('admins can create faqs', function () {
    $user = User::factory()->admin()->create();
    $course = Course::factory()->create();

    $this->actingAs($user)
        ->post("/admin/courses/{$course->id}/faqs", [
            'question' => 'What is this course about?',
            'answer' => 'This course teaches you programming.',
            'order' => 0,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('course_faqs', [
        'course_id' => $course->id,
        'question' => 'What is this course about?',
        'answer' => 'This course teaches you programming.',
        'order' => 0,
    ]);
});

test('faq creation requires valid data', function () {
    $user = User::factory()->admin()->create();
    $course = Course::factory()->create();

    $this->actingAs($user)
        ->post("/admin/courses/{$course->id}/faqs", [
            'question' => '',
            'answer' => '',
            'order' => -1,
        ])
        ->assertSessionHasErrors(['question', 'answer', 'order']);
});

test('non-admins cannot update faqs', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $course = Course::factory()->create();
    $faq = CourseFaq::factory()->for($course)->create();

    $this->actingAs($user)
        ->patch("/admin/courses/{$course->id}/faqs/{$faq->id}", [
            'question' => 'Updated question?',
        ])
        ->assertForbidden();
});

test('admins can update faqs', function () {
    $user = User::factory()->admin()->create();
    $course = Course::factory()->create();
    $faq = CourseFaq::factory()->for($course)->create([
        'question' => 'Original question?',
    ]);

    $this->actingAs($user)
        ->patch("/admin/courses/{$course->id}/faqs/{$faq->id}", [
            'question' => 'Updated question?',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('course_faqs', [
        'id' => $faq->id,
        'question' => 'Updated question?',
    ]);
});

test('admins can update faq answer', function () {
    $user = User::factory()->admin()->create();
    $course = Course::factory()->create();
    $faq = CourseFaq::factory()->for($course)->create([
        'answer' => 'Original answer.',
    ]);

    $this->actingAs($user)
        ->patch("/admin/courses/{$course->id}/faqs/{$faq->id}", [
            'answer' => 'Updated answer.',
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('course_faqs', [
        'id' => $faq->id,
        'answer' => 'Updated answer.',
    ]);
});

test('admins can update faq order', function () {
    $user = User::factory()->admin()->create();
    $course = Course::factory()->create();
    $faq = CourseFaq::factory()->for($course)->create(['order' => 0]);

    $this->actingAs($user)
        ->patch("/admin/courses/{$course->id}/faqs/{$faq->id}", [
            'order' => 5,
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('course_faqs', [
        'id' => $faq->id,
        'order' => 5,
    ]);
});

test('non-admins cannot delete faqs', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $course = Course::factory()->create();
    $faq = CourseFaq::factory()->for($course)->create();

    $this->actingAs($user)
        ->delete("/admin/courses/{$course->id}/faqs/{$faq->id}")
        ->assertForbidden();
});

test('admins can delete faqs', function () {
    $user = User::factory()->admin()->create();
    $course = Course::factory()->create();
    $faq = CourseFaq::factory()->for($course)->create();

    $this->actingAs($user)
        ->delete("/admin/courses/{$course->id}/faqs/{$faq->id}")
        ->assertRedirect();

    $this->assertDatabaseMissing('course_faqs', ['id' => $faq->id]);
});

test('faq must belong to course for update', function () {
    $user = User::factory()->admin()->create();
    $course1 = Course::factory()->create();
    $course2 = Course::factory()->create();
    $faq = CourseFaq::factory()->for($course1)->create();

    $this->actingAs($user)
        ->patch("/admin/courses/{$course2->id}/faqs/{$faq->id}", [
            'question' => 'Updated question?',
        ])
        ->assertNotFound();
});

test('faq must belong to course for delete', function () {
    $user = User::factory()->admin()->create();
    $course1 = Course::factory()->create();
    $course2 = Course::factory()->create();
    $faq = CourseFaq::factory()->for($course1)->create();

    $this->actingAs($user)
        ->delete("/admin/courses/{$course2->id}/faqs/{$faq->id}")
        ->assertNotFound();
});
