<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseFaq extends Model
{
    /** @use HasFactory<\Database\Factories\CourseFaqFactory> */
    use HasFactory;

    protected $fillable = [
        'course_id',
        'question',
        'answer',
        'order',
    ];

    /**
     * @return BelongsTo<Course, $this>
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
