# 7.2 Développement des composants d'accès aux données

> **Compétence visée :** Développer des composants d'accès aux données SQL et NoSQL

## Objectif

Cette section présente les composants d'accès aux données développés pour Mine Adventure, utilisant Eloquent ORM de Laravel pour les interactions avec la base de données relationnelle.

## ORM Eloquent

### Pourquoi Eloquent ?

| Avantage          | Description                                      |
|-------------------|--------------------------------------------------|
| **Abstraction**   | Pas de SQL brut, code plus lisible               |
| **Sécurité**      | Protection automatique contre les injections SQL |
| **Relations**     | Gestion intuitive des relations entre tables     |
| **Eager Loading** | Prévention des problèmes N+1                     |
| **Mutateurs**     | Transformation automatique des données           |
| **Events**        | Hooks sur les opérations CRUD                    |

## Modèles Eloquent

### Modèle Course

```php
<?php

namespace App\Models;

use App\Enums\CourseDifficulty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'thumbnail',
        'difficulty',
    ];

    /**
     * Cast automatique de l'attribut difficulty vers l'enum
     */
    protected function casts(): array
    {
        return [
            'difficulty' => CourseDifficulty::class,
        ];
    }

    /**
     * Relation : Un cours a plusieurs chapitres
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('position');
    }

    /**
     * Relation : Un cours a plusieurs FAQ
     */
    public function faqs(): HasMany
    {
        return $this->hasMany(CourseFaq::class)->orderBy('position');
    }

    /**
     * Accesseur : Nombre total de leçons dans le cours
     */
    public function getLessonsCountAttribute(): int
    {
        return $this->chapters->sum(fn ($chapter) => $chapter->lessons->count());
    }

    /**
     * Méthode : Calculer la progression d'un utilisateur
     */
    public function progressForUser(User $user): int
    {
        $totalLessons = $this->lessons_count;

        if ($totalLessons === 0) {
            return 0;
        }

        $completedLessons = LessonCompletion::where('user_id', $user->id)
            ->whereIn('lesson_id', $this->getAllLessonIds())
            ->count();

        return (int) round(($completedLessons / $totalLessons) * 100);
    }

    /**
     * Méthode privée : Récupérer tous les IDs de leçons
     */
    private function getAllLessonIds(): array
    {
        return $this->chapters
            ->flatMap(fn ($chapter) => $chapter->lessons->pluck('id'))
            ->toArray();
    }
}
```

### Modèle Lesson avec relations

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapter_id',
        'name',
        'position',
    ];

    /**
     * Relation : Une leçon appartient à un chapitre
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    /**
     * Relation : Une leçon a plusieurs blocs
     */
    public function blocks(): HasMany
    {
        return $this->hasMany(LessonBlock::class)->orderBy('position');
    }

    /**
     * Relation : Complétions de cette leçon
     */
    public function completions(): HasMany
    {
        return $this->hasMany(LessonCompletion::class);
    }

    /**
     * Méthode : Vérifier si la leçon est complétée par un utilisateur
     */
    public function isCompletedBy(User $user): bool
    {
        return $this->completions()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Méthode : Vérifier si la leçon est accessible par un utilisateur
     */
    public function isAccessibleBy(User $user): bool
    {
        // La première leçon est toujours accessible
        if ($this->position === 0) {
            return true;
        }

        // Sinon, la leçon précédente doit être complétée
        $previousLesson = $this->chapter->lessons()
            ->where('position', '<', $this->position)
            ->orderByDesc('position')
            ->first();

        return $previousLesson?->isCompletedBy($user) ?? true;
    }

    /**
     * Scope : Leçons accessibles pour un utilisateur
     */
    public function scopeAccessibleBy($query, User $user)
    {
        // Implémentation personnalisée selon les besoins
    }
}
```

### Modèle LessonBlock (polymorphique)

```php
<?php

namespace App\Models;

use App\Enums\BlockType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class LessonBlock extends Model
{
    protected $fillable = [
        'lesson_id',
        'type',
        'blockable_id',
        'blockable_type',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'type' => BlockType::class,
        ];
    }

    /**
     * Relation : Le bloc appartient à une leçon
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Relation polymorphique : Le contenu du bloc
     */
    public function blockable(): MorphTo
    {
        return $this->morphTo();
    }
}
```

### Modèle BlockAssignment

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class BlockAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructions',
        'starter_code',
        'language',
        'solution',
    ];

    /**
     * Relation polymorphique inverse
     */
    public function lessonBlock(): MorphOne
    {
        return $this->morphOne(LessonBlock::class, 'blockable');
    }

    /**
     * Relation : Tests JUnit associés
     */
    public function tests(): HasMany
    {
        return $this->hasMany(BlockAssignmentTest::class)->orderBy('position');
    }

    /**
     * Méthode : Compiler le code de test complet
     */
    public function getCompiledTestCode(): string
    {
        return $this->tests
            ->pluck('test_code')
            ->implode("\n\n");
    }
}
```

## Requêtes et accès aux données

### Eager Loading pour éviter N+1

```php
// ❌ Mauvaise pratique : N+1 queries
$courses = Course::all();
foreach ($courses as $course) {
    echo $course->chapters->count(); // Nouvelle requête à chaque itération
}

// ✅ Bonne pratique : Eager loading
$courses = Course::with(['chapters.lessons.blocks'])->get();
foreach ($courses as $course) {
    echo $course->chapters->count(); // Pas de requête supplémentaire
}
```

### Exemple dans un contrôleur

```php
<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    /**
     * Afficher la liste des cours avec leurs statistiques
     */
    public function index(): Response
    {
        $courses = Course::query()
            ->with(['chapters.lessons']) // Eager loading
            ->withCount(['chapters']) // Comptage optimisé
            ->orderBy('name')
            ->get()
            ->map(fn ($course) => [
                'id' => $course->id,
                'name' => $course->name,
                'thumbnail' => $course->thumbnail,
                'difficulty' => $course->difficulty,
                'chaptersCount' => $course->chapters_count,
                'lessonsCount' => $course->lessons_count,
                'progress' => auth()->check()
                    ? $course->progressForUser(auth()->user())
                    : null,
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
        // Charger les relations nécessaires
        $course->load([
            'chapters.lessons.blocks.blockable',
            'faqs',
        ]);

        $user = auth()->user();

        return Inertia::render('courses/show', [
            'course' => [
                'id' => $course->id,
                'name' => $course->name,
                'thumbnail' => $course->thumbnail,
                'difficulty' => $course->difficulty,
                'chapters' => $course->chapters->map(fn ($chapter) => [
                    'id' => $chapter->id,
                    'name' => $chapter->name,
                    'lessons' => $chapter->lessons->map(fn ($lesson) => [
                        'id' => $lesson->id,
                        'name' => $lesson->name,
                        'isCompleted' => $user ? $lesson->isCompletedBy($user) : false,
                        'isAccessible' => $user ? $lesson->isAccessibleBy($user) : false,
                    ]),
                ]),
                'faqs' => $course->faqs,
                'progress' => $user ? $course->progressForUser($user) : 0,
            ],
        ]);
    }
}
```

### Query Scopes

```php
// Dans le modèle Course
public function scopeByDifficulty($query, CourseDifficulty $difficulty)
{
    return $query->where('difficulty', $difficulty);
}

public function scopeWithProgress($query, User $user)
{
    // Ajoute les informations de progression
    return $query->with(['chapters.lessons' => function ($q) use ($user) {
        $q->withExists(['completions as is_completed' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }]);
    }]);
}

// Utilisation
$courses = Course::byDifficulty(CourseDifficulty::Easy)
    ->withProgress($user)
    ->get();
```

### Création et mise à jour

```php
// Création d'un cours avec chapitres
$course = Course::create([
    'name' => 'Introduction à Java',
    'difficulty' => CourseDifficulty::Easy,
]);

$chapter = $course->chapters()->create([
    'name' => 'Les bases',
    'position' => 0,
]);

$lesson = $chapter->lessons()->create([
    'name' => 'Variables et types',
    'position' => 0,
]);

// Création d'un bloc avec relation polymorphique
$assignment = BlockAssignment::create([
    'instructions' => 'Créez une variable...',
    'starter_code' => 'public class Main { }',
    'language' => 'java',
]);

$lesson->blocks()->create([
    'type' => BlockType::Assignment,
    'blockable_id' => $assignment->id,
    'blockable_type' => BlockAssignment::class,
    'position' => 0,
]);
```

### Transaction pour les opérations critiques

```php
use Illuminate\Support\Facades\DB;

public function createCourseWithContent(array $data): Course
{
    return DB::transaction(function () use ($data) {
        $course = Course::create([
            'name' => $data['name'],
            'difficulty' => $data['difficulty'],
        ]);

        foreach ($data['chapters'] as $chapterData) {
            $chapter = $course->chapters()->create([
                'name' => $chapterData['name'],
                'position' => $chapterData['position'],
            ]);

            foreach ($chapterData['lessons'] as $lessonData) {
                $chapter->lessons()->create([
                    'name' => $lessonData['name'],
                    'position' => $lessonData['position'],
                ]);
            }
        }

        return $course;
    });
}
```

## Requêtes SQL générées

### Exemple de requête avec Eager Loading

```php
Course::with(['chapters.lessons'])->get();
```

**SQL généré :**

```sql
SELECT * FROM courses;
SELECT * FROM chapters WHERE course_id IN (1, 2, 3, ...);
SELECT * FROM lessons WHERE chapter_id IN (1, 2, 3, 4, ...);
```

Au lieu de N+1 requêtes, nous n'avons que 3 requêtes, quelle que soit la quantité de données.

## Factories pour les tests

```php
<?php

namespace Database\Factories;

use App\Enums\CourseDifficulty;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'thumbnail' => fake()->optional()->imageUrl(640, 360),
            'difficulty' => fake()->randomElement(CourseDifficulty::cases()),
        ];
    }

    public function easy(): static
    {
        return $this->state(['difficulty' => CourseDifficulty::Easy]);
    }

    public function withChapters(int $count = 3): static
    {
        return $this->afterCreating(function ($course) use ($count) {
            Chapter::factory()->count($count)->create([
                'course_id' => $course->id,
            ]);
        });
    }
}
```

## Bonnes pratiques appliquées

| Pratique                 | Application                                        |
|--------------------------|----------------------------------------------------|
| **Eloquent ORM**         | Pas de SQL brut, abstraction complète              |
| **Eager Loading**        | Prévention systématique des N+1                    |
| **Query Scopes**         | Requêtes réutilisables et lisibles                 |
| **Transactions**         | Intégrité des données sur les opérations multiples |
| **Factories**            | Données de test cohérentes                         |
| **Type hints**           | Retours typés sur toutes les méthodes              |
| **Relations explicites** | Documentation des relations dans les modèles       |
