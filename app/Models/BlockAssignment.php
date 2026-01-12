<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlockAssignment extends Model
{
    /** @use HasFactory<\Database\Factories\BlockAssignmentFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'block_id',
        'instructions',
        'starter_code',
        'solution',
        'language',
    ];

    /**
     * @return BelongsTo<LessonBlock, $this>
     */
    public function block(): BelongsTo
    {
        return $this->belongsTo(LessonBlock::class, 'block_id');
    }

    /**
     * @return HasMany<BlockAssignmentTest, $this>
     */
    public function tests(): HasMany
    {
        return $this->hasMany(BlockAssignmentTest::class);
    }
}
