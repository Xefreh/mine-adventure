# 7.2 Développement des composants d'accès aux données

> **Compétence visée :** Développer des composants d'accès aux données SQL et NoSQL

## Objectif

Cette section présente les composants d'accès aux données développés pour Mine Adventure, utilisant Eloquent ORM de Laravel.

## ORM Eloquent

Eloquent offre une **abstraction** sans SQL brut, une **sécurité** contre les injections SQL, une gestion intuitive des **relations**, et l'**Eager Loading** pour prévenir les problèmes N+1.

## Modèles principaux

### Modèle Course

```php
class Course extends Model
{
    protected $fillable = ['name', 'thumbnail', 'difficulty'];

    protected function casts(): array
    {
        return ['difficulty' => CourseDifficulty::class];
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('position');
    }

    public function progressForUser(User $user): int
    {
        $total = $this->lessons_count;
        if ($total === 0) return 0;
        $completed = LessonCompletion::where('user_id', $user->id)
            ->whereIn('lesson_id', $this->getAllLessonIds())->count();
        return (int) round(($completed / $total) * 100);
    }
}
```

### Modèle Lesson

```php
class Lesson extends Model
{
    public function isAccessibleBy(User $user): bool
    {
        if ($this->position === 0) return true;
        $previous = $this->chapter->lessons()
            ->where('position', '<', $this->position)
            ->orderByDesc('position')->first();
        return $previous?->isCompletedBy($user) ?? true;
    }
}
```

### Modèle LessonBlock (polymorphique)

Le système de blocs utilise une relation polymorphique pour supporter différents types de contenu (vidéo, texte, quiz, exercice).

```php
class LessonBlock extends Model
{
    protected function casts(): array
    {
        return ['type' => BlockType::class];
    }

    public function blockable(): MorphTo
    {
        return $this->morphTo();
    }
}
```

## Eager Loading

```php
// ❌ N+1 queries
foreach (Course::all() as $course) {
    echo $course->chapters->count();
}

// ✅ Eager loading (3 requêtes au lieu de N+1)
$courses = Course::with(['chapters.lessons'])->get();
```

## Query Scopes

```php
public function scopeByDifficulty($query, CourseDifficulty $difficulty)
{
    return $query->where('difficulty', $difficulty);
}

// Usage
$courses = Course::byDifficulty(CourseDifficulty::Easy)->get();
```

## Factories

```php
class CourseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'difficulty' => fake()->randomElement(CourseDifficulty::cases()),
        ];
    }

    public function withChapters(int $count = 3): static
    {
        return $this->afterCreating(fn ($course) =>
            Chapter::factory()->count($count)->create(['course_id' => $course->id])
        );
    }
}
```

## Bonnes pratiques

L'**Eloquent ORM** est utilisé exclusivement sans SQL brut. L'**Eager Loading** prévient les N+1. Les **Query Scopes** créent des requêtes réutilisables. Les **transactions** assurent l'intégrité des données. Les **factories** garantissent des tests cohérents.
