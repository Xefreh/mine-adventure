# 7.3 Développement des composants métier côté serveur

> **Compétence visée :** Développer des composants métier côté serveur

## Objectif

Cette section présente les composants métier développés pour Mine Adventure : contrôleurs, services, validation des données, et intégration avec l'API externe Judge0 pour l'exécution de code.

## Architecture des composants

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/                    # Contrôleurs d'administration
│   │   │   ├── CourseController.php
│   │   │   ├── ChapterController.php
│   │   │   ├── LessonController.php
│   │   │   └── BlockController.php
│   │   ├── CourseController.php      # Contrôleurs publics
│   │   ├── LessonController.php
│   │   └── AssignmentController.php
│   └── Requests/                     # Form Requests (validation)
│       ├── StoreCourseRequest.php
│       ├── UpdateCourseRequest.php
│       └── SubmitCodeRequest.php
├── Services/                         # Services métier
│   └── TestSubmissionService.php
└── Enums/                            # Enums PHP
    ├── BlockType.php
    └── CourseDifficulty.php
```

## Contrôleurs

### Contrôleur Course (Public)

```php
<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    /**
     * Afficher la liste des cours disponibles
     */
    public function index(): Response
    {
        $user = auth()->user();

        $courses = Course::query()
            ->with(['chapters.lessons'])
            ->get()
            ->map(fn ($course) => [
                'id' => $course->id,
                'name' => $course->name,
                'thumbnail' => $course->thumbnail,
                'difficulty' => $course->difficulty,
                'chaptersCount' => $course->chapters->count(),
                'lessonsCount' => $course->lessons_count,
                'progress' => $user ? $course->progressForUser($user) : null,
            ]);

        return Inertia::render('courses/index', [
            'courses' => $courses,
        ]);
    }

    /**
     * Afficher le détail d'un cours
     */
    public function show(Course $course): Response
    {
        $user = auth()->user();

        $course->load(['chapters.lessons', 'faqs']);

        return Inertia::render('courses/show', [
            'course' => [
                'id' => $course->id,
                'name' => $course->name,
                'thumbnail' => $course->thumbnail,
                'difficulty' => $course->difficulty,
                'progress' => $user ? $course->progressForUser($user) : 0,
                'chapters' => $course->chapters->map(fn ($chapter) => [
                    'id' => $chapter->id,
                    'name' => $chapter->name,
                    'lessons' => $chapter->lessons->map(fn ($lesson) => [
                        'id' => $lesson->id,
                        'name' => $lesson->name,
                        'isCompleted' => $user?->hasCompletedLesson($lesson) ?? false,
                        'isAccessible' => $user ? $lesson->isAccessibleBy($user) : false,
                    ]),
                ]),
                'faqs' => $course->faqs,
            ],
        ]);
    }
}
```

### Contrôleur Lesson avec complétion

```php
<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class LessonController extends Controller
{
    /**
     * Afficher une leçon
     */
    public function show(Course $course, Lesson $lesson): Response
    {
        $user = auth()->user();

        // Vérifier l'accès à la leçon
        if (!$lesson->isAccessibleBy($user)) {
            abort(403, 'Vous devez compléter les leçons précédentes.');
        }

        $lesson->load(['blocks.blockable']);

        return Inertia::render('lessons/show', [
            'course' => [
                'id' => $course->id,
                'name' => $course->name,
            ],
            'lesson' => [
                'id' => $lesson->id,
                'name' => $lesson->name,
                'isCompleted' => $lesson->isCompletedBy($user),
                'blocks' => $lesson->blocks->map(fn ($block) => [
                    'id' => $block->id,
                    'type' => $block->type,
                    'content' => $this->formatBlockContent($block),
                    'position' => $block->position,
                ]),
            ],
            'navigation' => $this->getLessonNavigation($course, $lesson),
        ]);
    }

    /**
     * Marquer une leçon comme complétée
     */
    public function complete(Course $course, Lesson $lesson): RedirectResponse
    {
        $user = auth()->user();

        // Vérifier que la leçon n'est pas déjà complétée
        if ($lesson->isCompletedBy($user)) {
            return back();
        }

        // Créer l'enregistrement de complétion
        LessonCompletion::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Leçon complétée !');
    }

    /**
     * Formater le contenu d'un bloc selon son type
     */
    private function formatBlockContent($block): array
    {
        return match ($block->type->value) {
            'video' => [
                'url' => $block->blockable->url,
                'duration' => $block->blockable->duration,
            ],
            'text' => [
                'content' => $block->blockable->content,
            ],
            'resources' => [
                'links' => $block->blockable->links,
            ],
            'quiz' => [
                'question' => $block->blockable->question,
                'options' => $block->blockable->options,
            ],
            'assignment' => [
                'id' => $block->blockable->id,
                'instructions' => $block->blockable->instructions,
                'starterCode' => $block->blockable->starter_code,
                'language' => $block->blockable->language,
            ],
            default => [],
        };
    }

    /**
     * Obtenir la navigation entre leçons
     */
    private function getLessonNavigation(Course $course, Lesson $currentLesson): array
    {
        $allLessons = $course->chapters
            ->flatMap(fn ($c) => $c->lessons)
            ->values();

        $currentIndex = $allLessons->search(fn ($l) => $l->id === $currentLesson->id);

        return [
            'previous' => $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null,
            'next' => $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null,
            'current' => $currentIndex + 1,
            'total' => $allLessons->count(),
        ];
    }
}
```

### Contrôleur Assignment (Exécution de code)

```php
<?php

namespace App\Http\Controllers;

use App\Models\BlockAssignment;
use App\Services\TestSubmissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AssignmentController extends Controller
{
    public function __construct(
        private TestSubmissionService $testService
    ) {}

    /**
     * Exécuter le code sans tests (mode "Run")
     */
    public function run(Request $request, BlockAssignment $assignment): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $result = $this->executeCode($request->code);

        return response()->json([
            'output' => $result['stdout'] ?? '',
            'error' => $result['stderr'] ?? $result['compile_output'] ?? null,
            'status' => $result['status']['description'] ?? 'Unknown',
            'time' => $result['time'] ?? null,
            'memory' => $result['memory'] ?? null,
        ]);
    }

    /**
     * Soumettre le code avec exécution des tests JUnit
     */
    public function submit(Request $request, BlockAssignment $assignment): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        // Utiliser le service pour exécuter les tests
        $testResults = $this->testService->runTests(
            $request->code,
            $assignment
        );

        return response()->json([
            'tests' => $testResults,
            'allPassed' => collect($testResults)->every(fn ($t) => $t['passed']),
        ]);
    }

    /**
     * Exécuter du code via l'API Judge0
     */
    private function executeCode(string $code): array
    {
        $response = Http::withHeaders([
            'X-Auth-Token' => config('services.judge0.api_key'),
        ])->post(config('services.judge0.url') . '/submissions?wait=true', [
            'source_code' => base64_encode($code),
            'language_id' => 62, // Java
            'stdin' => '',
        ]);

        $result = $response->json();

        // Décoder les résultats base64
        return [
            'stdout' => isset($result['stdout']) ? base64_decode($result['stdout']) : null,
            'stderr' => isset($result['stderr']) ? base64_decode($result['stderr']) : null,
            'compile_output' => isset($result['compile_output']) ? base64_decode($result['compile_output']) : null,
            'status' => $result['status'] ?? null,
            'time' => $result['time'] ?? null,
            'memory' => $result['memory'] ?? null,
        ];
    }
}
```

## Services métier

### Service d'exécution des tests

```php
<?php

namespace App\Services;

use App\Models\BlockAssignment;
use Illuminate\Support\Facades\Http;

class TestSubmissionService
{
    /**
     * Exécuter les tests JUnit sur le code soumis
     */
    public function runTests(string $userCode, BlockAssignment $assignment): array
    {
        // Construire le code complet avec les tests
        $fullCode = $this->buildTestCode($userCode, $assignment);

        // Exécuter via Judge0
        $result = $this->executeWithJudge0($fullCode);

        // Parser les résultats JUnit
        return $this->parseTestResults($result['stdout'] ?? '');
    }

    /**
     * Construire le code de test complet
     */
    private function buildTestCode(string $userCode, BlockAssignment $assignment): string
    {
        $testCode = $assignment->getCompiledTestCode();

        // Template JUnit 5 avec le code utilisateur intégré
        return <<<JAVA
        import org.junit.jupiter.api.*;
        import static org.junit.jupiter.api.Assertions.*;

        // Code de l'utilisateur
        {$userCode}

        // Tests JUnit
        class UserCodeTest {
            {$testCode}
        }

        // Runner de tests simplifié
        public class TestRunner {
            public static void main(String[] args) {
                // Exécution des tests et affichage des résultats
                org.junit.platform.launcher.Launcher launcher =
                    org.junit.platform.launcher.core.LauncherFactory.create();

                // ... configuration et exécution
            }
        }
        JAVA;
    }

    /**
     * Exécuter le code via Judge0
     */
    private function executeWithJudge0(string $code): array
    {
        $response = Http::withHeaders([
            'X-Auth-Token' => config('services.judge0.api_key'),
        ])->timeout(60)->post(config('services.judge0.url') . '/submissions?wait=true', [
            'source_code' => base64_encode($code),
            'language_id' => 62, // Java
            'additional_files' => $this->getJUnitDependencies(),
        ]);

        $result = $response->json();

        return [
            'stdout' => isset($result['stdout']) ? base64_decode($result['stdout']) : '',
            'stderr' => isset($result['stderr']) ? base64_decode($result['stderr']) : '',
            'status' => $result['status'] ?? null,
        ];
    }

    /**
     * Parser la sortie JUnit pour extraire les résultats
     */
    private function parseTestResults(string $output): array
    {
        $results = [];
        $lines = explode("\n", $output);

        foreach ($lines as $line) {
            if (preg_match('/^(PASS|FAIL):\s+(.+)$/', $line, $matches)) {
                $results[] = [
                    'name' => trim($matches[2]),
                    'passed' => $matches[1] === 'PASS',
                    'message' => null,
                ];
            } elseif (preg_match('/^ERROR:\s+(.+):\s+(.+)$/', $line, $matches)) {
                $results[] = [
                    'name' => trim($matches[1]),
                    'passed' => false,
                    'message' => trim($matches[2]),
                ];
            }
        }

        return $results;
    }

    /**
     * Obtenir les dépendances JUnit
     */
    private function getJUnitDependencies(): string
    {
        // Fichiers JAR JUnit encodés en base64 ou URL
        return '';
    }
}
```

## Validation des données

### Form Request pour la création de cours

```php
<?php

namespace App\Http\Requests;

use App\Enums\CourseDifficulty;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'thumbnail' => ['nullable', 'url', 'max:500'],
            'difficulty' => ['required', Rule::enum(CourseDifficulty::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du cours est obligatoire.',
            'name.min' => 'Le nom doit contenir au moins 3 caractères.',
            'difficulty.required' => 'La difficulté est obligatoire.',
        ];
    }
}
```

### Form Request pour la soumission de code

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'max:50000', // Limite de taille
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Le code est obligatoire.',
            'code.max' => 'Le code est trop long (max 50000 caractères).',
        ];
    }
}
```

## Contrôleur Admin

### Gestion CRUD des cours

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    public function index(): Response
    {
        $courses = Course::withCount(['chapters'])
            ->latest()
            ->get();

        return Inertia::render('admin/courses/index', [
            'courses' => $courses,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/courses/create');
    }

    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $course = Course::create($request->validated());

        return redirect()
            ->route('admin.courses.edit', $course)
            ->with('success', 'Cours créé avec succès.');
    }

    public function edit(Course $course): Response
    {
        $course->load(['chapters.lessons.blocks']);

        return Inertia::render('admin/courses/edit', [
            'course' => $course,
        ]);
    }

    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $course->update($request->validated());

        return back()->with('success', 'Cours mis à jour.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Cours supprimé.');
    }
}
```

## Middleware et autorisations

### Middleware Admin

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()?->is_admin) {
            abort(403, 'Accès réservé aux administrateurs.');
        }

        return $next($request);
    }
}
```

### Enregistrement dans bootstrap/app.php

```php
<?php

use App\Http\Middleware\EnsureUserIsAdmin;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(...)
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
        ]);
    })
    ->create();
```

## Routes

```php
<?php

// routes/lms.php
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\Admin;

// Routes publiques (authentifiées)
Route::middleware('auth')->group(function () {
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
    Route::post('/courses/{course}/lessons/{lesson}/complete', [LessonController::class, 'complete'])->name('lessons.complete');

    // Exécution de code
    Route::post('/assignments/{assignment}/run', [AssignmentController::class, 'run'])->name('assignments.run');
    Route::post('/assignments/{assignment}/submit', [AssignmentController::class, 'submit'])->name('assignments.submit');
});

// Routes administration
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('courses', Admin\CourseController::class);
    Route::resource('courses.chapters', Admin\ChapterController::class)->shallow();
    Route::resource('chapters.lessons', Admin\LessonController::class)->shallow();
    Route::resource('lessons.blocks', Admin\BlockController::class)->shallow();
});
```

## Tests unitaires et fonctionnels

```php
<?php

use App\Models\Course;
use App\Models\User;

it('can list courses', function () {
    Course::factory()->count(3)->create();

    $this->actingAs(User::factory()->create())
        ->get('/courses')
        ->assertOk()
        ->assertInertia(fn ($page) =>
            $page->component('courses/index')
                 ->has('courses', 3)
        );
});

it('can complete a lesson', function () {
    $user = User::factory()->create();
    $course = Course::factory()->withChapters(1)->create();
    $lesson = $course->chapters->first()->lessons->first();

    $this->actingAs($user)
        ->post("/courses/{$course->id}/lessons/{$lesson->id}/complete")
        ->assertRedirect();

    expect($lesson->isCompletedBy($user))->toBeTrue();
});
```
