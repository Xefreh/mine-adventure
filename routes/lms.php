<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CodeExecutionController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use Illuminate\Support\Facades\Route;
use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;

// Public course routes
Route::middleware(['auth', ValidateSessionWithWorkOS::class])->group(function () {
    Route::get('courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('courses/{course}', [CourseController::class, 'show'])->name('courses.show');

    Route::scopeBindings()->prefix('courses/{course}')->name('courses.')->group(function () {
        Route::get('lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
        Route::post('lessons/{lesson}/complete', [LessonController::class, 'complete'])->name('lessons.complete');
    });

    // Code execution routes
    Route::prefix('assignments/{assignment}')->name('assignments.')->group(function () {
        Route::post('run', [CodeExecutionController::class, 'run'])->name('run');
        Route::post('submit', [CodeExecutionController::class, 'submit'])->name('submit');
    });
});

// Admin routes
Route::middleware(['auth', ValidateSessionWithWorkOS::class, 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', Admin\AdminDashboardController::class)->name('dashboard');
        Route::resource('courses', Admin\CourseController::class);

        // Chapter management (nested under course)
        Route::scopeBindings()->prefix('courses/{course}')->name('courses.')->group(function () {
            Route::get('chapters', [Admin\ChapterController::class, 'index'])->name('chapters.index');
            Route::post('chapters', [Admin\ChapterController::class, 'store'])->name('chapters.store');
            Route::patch('chapters/{chapter}', [Admin\ChapterController::class, 'update'])->name('chapters.update');
            Route::delete('chapters/{chapter}', [Admin\ChapterController::class, 'destroy'])->name('chapters.destroy');
            Route::post('chapters/reorder', [Admin\ChapterController::class, 'reorder'])->name('chapters.reorder');

            // FAQ management
            Route::post('faqs', [Admin\FaqController::class, 'store'])->name('faqs.store');
            Route::patch('faqs/{faq}', [Admin\FaqController::class, 'update'])->name('faqs.update');
            Route::delete('faqs/{faq}', [Admin\FaqController::class, 'destroy'])->name('faqs.destroy');
        });

        // Lesson management (nested under chapter)
        Route::scopeBindings()->prefix('chapters/{chapter}')->name('chapters.')->group(function () {
            Route::get('lessons', [Admin\LessonController::class, 'index'])->name('lessons.index');
            Route::get('lessons/create', [Admin\LessonController::class, 'create'])->name('lessons.create');
            Route::post('lessons', [Admin\LessonController::class, 'store'])->name('lessons.store');
            Route::get('lessons/{lesson}', [Admin\LessonController::class, 'edit'])->name('lessons.edit');
            Route::patch('lessons/{lesson}', [Admin\LessonController::class, 'update'])->name('lessons.update');
            Route::delete('lessons/{lesson}', [Admin\LessonController::class, 'destroy'])->name('lessons.destroy');
        });

        // Block management (nested under lesson)
        Route::scopeBindings()->prefix('lessons/{lesson}')->name('lessons.')->group(function () {
            Route::post('blocks', [Admin\BlockController::class, 'store'])->name('blocks.store');
            Route::patch('blocks/{block}', [Admin\BlockController::class, 'update'])->name('blocks.update');
            Route::delete('blocks/{block}', [Admin\BlockController::class, 'destroy'])->name('blocks.destroy');
            Route::post('blocks/reorder', [Admin\BlockController::class, 'reorder'])->name('blocks.reorder');
            Route::post('blocks/delete-multiple', [Admin\BlockController::class, 'destroyMultiple'])->name('blocks.destroy-multiple');
        });
    });
