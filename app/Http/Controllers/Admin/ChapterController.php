<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReorderChaptersRequest;
use App\Http\Requests\Admin\StoreChapterRequest;
use App\Http\Requests\Admin\UpdateChapterRequest;
use App\Models\Chapter;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ChapterController extends Controller
{
    public function index(Course $course): Response
    {
        $chapters = $course->chapters()
            ->withCount('lessons')
            ->orderBy('position')
            ->get();

        return Inertia::render('admin/chapters/index', [
            'course' => $course,
            'chapters' => $chapters,
        ]);
    }

    public function store(StoreChapterRequest $request, Course $course): RedirectResponse
    {
        $course->chapters()->create($request->validated());

        return redirect()->back()
            ->with('success', 'Chapter created successfully.');
    }

    public function update(UpdateChapterRequest $request, Course $course, Chapter $chapter): RedirectResponse
    {
        $chapter->update($request->validated());

        return redirect()->back()
            ->with('success', 'Chapter updated successfully.');
    }

    public function destroy(Course $course, Chapter $chapter): RedirectResponse
    {
        $chapter->delete();

        return redirect()->back()
            ->with('success', 'Chapter deleted successfully.');
    }

    public function reorder(ReorderChaptersRequest $request, Course $course): RedirectResponse
    {
        $chapters = $request->validated()['chapters'];

        // Use a transaction and temporarily set positions to high values to avoid unique constraint violations
        \DB::transaction(function () use ($chapters, $course) {
            // First, set all positions to high temporary values
            foreach ($chapters as $index => $chapterData) {
                Chapter::where('id', $chapterData['id'])
                    ->where('course_id', $course->id)
                    ->update(['position' => 10000 + $index]);
            }

            // Then set the actual positions
            foreach ($chapters as $chapterData) {
                Chapter::where('id', $chapterData['id'])
                    ->where('course_id', $course->id)
                    ->update(['position' => $chapterData['position']]);
            }
        });

        return redirect()->back()
            ->with('success', 'Chapters reordered successfully.');
    }
}
