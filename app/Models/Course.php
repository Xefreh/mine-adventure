<?php

namespace App\Models;

use App\Enums\CourseDifficulty;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'thumbnail',
        'description',
        'difficulty',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'difficulty' => CourseDifficulty::class,
        ];
    }

    /**
     * @return HasMany<Chapter, $this>
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('position');
    }

    /**
     * @return HasMany<CourseFaq, $this>
     */
    public function faqs(): HasMany
    {
        return $this->hasMany(CourseFaq::class)->orderBy('order');
    }

    /**
     * @return HasManyThrough<Lesson, Chapter, $this>
     */
    public function lessons(): HasManyThrough
    {
        return $this->hasManyThrough(Lesson::class, Chapter::class);
    }
}
