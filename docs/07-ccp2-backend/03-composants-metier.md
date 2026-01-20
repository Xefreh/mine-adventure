# 7.3 Développement des composants métier côté serveur

> **Compétence visée :** Développer des composants métier côté serveur

## Objectif

Cette section présente les composants métier développés pour Mine Adventure : contrôleurs, services, validation des données, et intégration avec l'API externe Judge0 pour l'exécution de code.

## Architecture des composants

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/           # CRUD administration
│   │   ├── CourseController.php
│   │   ├── LessonController.php
│   │   └── AssignmentController.php
│   └── Requests/            # Validation
├── Services/
│   └── TestSubmissionService.php
└── Enums/
    ├── BlockType.php
    └── CourseDifficulty.php
```

## Contrôleurs

### Contrôleur Course (Public)

Le `CourseController` gère l'affichage des cours avec eager loading des relations et calcul de la progression utilisateur.

```php
class CourseController extends Controller
{
    public function index(): Response
    {
        $courses = Course::with(['chapters.lessons'])
            ->get()
            ->map(fn ($course) => [
                'id' => $course->id,
                'name' => $course->name,
                'progress' => $course->progressForUser(auth()->user()),
            ]);

        return Inertia::render('courses/index', ['courses' => $courses]);
    }
}
```

### Contrôleur Lesson

Le `LessonController` vérifie l'accès séquentiel aux leçons et gère leur complétion.

```php
class LessonController extends Controller
{
    public function show(Course $course, Lesson $lesson): Response
    {
        if (!$lesson->isAccessibleBy(auth()->user())) {
            abort(403, 'Vous devez compléter les leçons précédentes.');
        }

        return Inertia::render('lessons/show', [
            'lesson' => $lesson->load('blocks.blockable'),
        ]);
    }

    public function complete(Course $course, Lesson $lesson): RedirectResponse
    {
        LessonCompletion::create([
            'user_id' => auth()->id(),
            'lesson_id' => $lesson->id,
        ]);

        return back()->with('success', 'Leçon complétée !');
    }
}
```

### Contrôleur Assignment (Exécution de code)

L'`AssignmentController` expose deux endpoints : `run` pour l'exécution simple et `submit` pour la validation par tests JUnit.

```php
class AssignmentController extends Controller
{
    public function __construct(private TestSubmissionService $testService) {}

    public function run(Request $request, BlockAssignment $assignment): JsonResponse
    {
        $result = $this->executeCode($request->validated('code'));
        return response()->json($result);
    }

    public function submit(Request $request, BlockAssignment $assignment): JsonResponse
    {
        $testResults = $this->testService->runTests($request->code, $assignment);
        return response()->json([
            'tests' => $testResults,
            'allPassed' => collect($testResults)->every(fn ($t) => $t['passed']),
        ]);
    }
}
```

## Service d'exécution des tests

Le `TestSubmissionService` orchestre l'exécution des tests JUnit via Judge0 en construisant le code complet, l'envoyant à l'API, et parsant les résultats.

```php
class TestSubmissionService
{
    public function runTests(string $userCode, BlockAssignment $assignment): array
    {
        $fullCode = $this->buildTestCode($userCode, $assignment);
        $result = $this->executeWithJudge0($fullCode);
        return $this->parseTestResults($result['stdout'] ?? '');
    }

    private function executeWithJudge0(string $code): array
    {
        return Http::withHeaders(['X-Auth-Token' => config('services.judge0.api_key')])
            ->timeout(60)
            ->post(config('services.judge0.url') . '/submissions?wait=true', [
                'source_code' => base64_encode($code),
                'language_id' => 62, // Java
            ])
            ->json();
    }
}
```

## Validation des données

Les Form Requests centralisent la validation et l'autorisation. Exemple pour la création de cours :

```php
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
            'difficulty' => ['required', Rule::enum(CourseDifficulty::class)],
        ];
    }
}
```

## Middleware Admin

Un middleware protège les routes d'administration :

```php
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()?->is_admin) {
            abort(403);
        }
        return $next($request);
    }
}
```

## Routes

Les routes sont organisées en deux groupes : publiques (authentifiées) et administration.

```php
// Routes publiques
Route::middleware('auth')->group(function () {
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{course}/lessons/{lesson}', [LessonController::class, 'show']);
    Route::post('/assignments/{assignment}/submit', [AssignmentController::class, 'submit']);
});

// Routes administration
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::resource('courses', Admin\CourseController::class);
});
```

## Tests

```php
it('can complete a lesson', function () {
    $user = User::factory()->create();
    $lesson = Lesson::factory()->create();

    $this->actingAs($user)
        ->post("/courses/{$lesson->chapter->course_id}/lessons/{$lesson->id}/complete")
        ->assertRedirect();

    expect($lesson->isCompletedBy($user))->toBeTrue();
});
```
