<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLessonRequest;
use App\Http\Requests\Admin\UpdateLessonRequest;
use App\Models\Chapter;
use App\Models\Lesson;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class LessonController extends Controller
{
    public function index(Chapter $chapter): Response
    {
        $lessons = $chapter->lessons()
            ->withCount('blocks')
            ->get();

        return Inertia::render('admin/lessons/index', [
            'chapter' => $chapter->load('course'),
            'lessons' => $lessons,
        ]);
    }

    public function create(Chapter $chapter): Response
    {
        return Inertia::render('admin/lessons/create', [
            'chapter' => $chapter->load('course'),
        ]);
    }

    public function store(StoreLessonRequest $request, Chapter $chapter): RedirectResponse
    {
        $chapter->lessons()->create($request->validated());

        return redirect()->route('admin.chapters.lessons.index', $chapter)
            ->with('success', 'Lesson created successfully.');
    }

    public function edit(Chapter $chapter, Lesson $lesson): Response
    {
        $lesson->load([
            'blocks' => function ($query) {
                $query->orderBy('position');
            },
            'blocks.video',
            'blocks.text',
            'blocks.resource',
            'blocks.assignment',
            'blocks.assignment.test',
            'blocks.quiz',
            'blocks.quiz.questions',
        ]);

        return Inertia::render('admin/lessons/edit', [
            'chapter' => $chapter->load('course'),
            'lesson' => $lesson,
        ]);
    }

    public function update(UpdateLessonRequest $request, Chapter $chapter, Lesson $lesson): RedirectResponse
    {
        $lesson->update($request->validated());

        return redirect()->back()
            ->with('success', 'Lesson updated successfully.');
    }

    public function destroy(Chapter $chapter, Lesson $lesson): RedirectResponse
    {
        $lesson->delete();

        return redirect()->route('admin.chapters.lessons.index', $chapter)
            ->with('success', 'Lesson deleted successfully.');
    }
}
