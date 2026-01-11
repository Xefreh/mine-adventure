<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockQuizQuestion extends Model
{
    /** @use HasFactory<\Database\Factories\BlockQuizQuestionFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'block_quiz_id',
        'question',
        'options',
        'correct_answer',
        'position',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'options' => 'array',
        ];
    }

    /**
     * @return BelongsTo<BlockQuiz, $this>
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(BlockQuiz::class, 'block_quiz_id');
    }
}
