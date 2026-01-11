<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlockQuiz extends Model
{
    /** @use HasFactory<\Database\Factories\BlockQuizFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'block_id',
    ];

    /**
     * @return BelongsTo<LessonBlock, $this>
     */
    public function block(): BelongsTo
    {
        return $this->belongsTo(LessonBlock::class, 'block_id');
    }

    /**
     * @return HasMany<BlockQuizQuestion, $this>
     */
    public function questions(): HasMany
    {
        return $this->hasMany(BlockQuizQuestion::class)->orderBy('position');
    }
}
