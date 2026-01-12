<?php

use App\Models\Course;
use App\Models\CourseFaq;

test('course faq belongs to course', function () {
    $course = Course::factory()->create();
    $faq = CourseFaq::factory()->create(['course_id' => $course->id]);

    expect($faq->course)->toBeInstanceOf(Course::class);
    expect($faq->course->id)->toBe($course->id);
});

test('course has faqs relationship', function () {
    $course = Course::factory()->create();
    CourseFaq::factory()->count(3)->for($course)->create();

    expect($course->faqs)->toHaveCount(3);
});

test('course faqs are ordered by order field', function () {
    $course = Course::factory()->create();
    CourseFaq::factory()->for($course)->create(['order' => 2]);
    CourseFaq::factory()->for($course)->create(['order' => 0]);
    CourseFaq::factory()->for($course)->create(['order' => 1]);

    $faqs = $course->faqs;

    expect($faqs[0]->order)->toBe(0);
    expect($faqs[1]->order)->toBe(1);
    expect($faqs[2]->order)->toBe(2);
});

test('course faq has question and answer', function () {
    $faq = CourseFaq::factory()->create([
        'question' => 'What is PHP?',
        'answer' => 'PHP is a programming language.',
    ]);

    expect($faq->question)->toBe('What is PHP?');
    expect($faq->answer)->toBe('PHP is a programming language.');
});

test('course faqs are deleted when course is deleted', function () {
    $course = Course::factory()->create();
    CourseFaq::factory()->count(3)->for($course)->create();

    $course->delete();

    expect(CourseFaq::where('course_id', $course->id)->count())->toBe(0);
});
