<?php

namespace App\Models;

use App\Enums\BlockType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LessonBlock extends Model
{
    /** @use HasFactory<\Database\Factories\LessonBlockFactory> */
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'type',
        'position',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => BlockType::class,
        ];
    }

    /**
     * @return BelongsTo<Lesson, $this>
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * @return HasOne<BlockVideo, $this>
     */
    public function video(): HasOne
    {
        return $this->hasOne(BlockVideo::class, 'block_id');
    }

    /**
     * @return HasOne<BlockText, $this>
     */
    public function text(): HasOne
    {
        return $this->hasOne(BlockText::class, 'block_id');
    }

    /**
     * @return HasOne<BlockResource, $this>
     */
    public function resource(): HasOne
    {
        return $this->hasOne(BlockResource::class, 'block_id');
    }

    /**
     * @return HasOne<BlockAssignment, $this>
     */
    public function assignment(): HasOne
    {
        return $this->hasOne(BlockAssignment::class, 'block_id');
    }

    /**
     * @return HasOne<BlockQuiz, $this>
     */
    public function quiz(): HasOne
    {
        return $this->hasOne(BlockQuiz::class, 'block_id');
    }

    /**
     * Get the content for this block based on its type.
     */
    public function getContentAttribute(): BlockVideo|BlockText|BlockResource|BlockAssignment|BlockQuiz|null
    {
        return match ($this->type) {
            BlockType::Video => $this->video,
            BlockType::Text => $this->text,
            BlockType::Resources => $this->resource,
            BlockType::Assignment => $this->assignment,
            BlockType::Quiz => $this->quiz,
            default => null,
        };
    }
}
