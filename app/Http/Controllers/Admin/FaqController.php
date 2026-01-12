<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseFaq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function store(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'question' => ['required', 'string'],
            'answer' => ['required', 'string'],
            'order' => ['required', 'integer', 'min:0'],
        ]);

        $course->faqs()->create($validated);

        return redirect()->back()
            ->with('success', 'FAQ created successfully.');
    }

    public function update(Request $request, Course $course, CourseFaq $faq): RedirectResponse
    {
        $validated = $request->validate([
            'question' => ['sometimes', 'string'],
            'answer' => ['sometimes', 'string'],
            'order' => ['sometimes', 'integer', 'min:0'],
        ]);

        $faq->update($validated);

        return redirect()->back()
            ->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Course $course, CourseFaq $faq): RedirectResponse
    {
        $faq->delete();

        return redirect()->back()
            ->with('success', 'FAQ deleted successfully.');
    }
}
