<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $courses = Course::withCount('chapters')->get();

        return Inertia::render('dashboard', [
            'courses' => $courses,
        ]);
    }
}
