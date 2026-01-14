<?php

namespace App\Models;

use Database\Factories\BlockAssignmentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BlockAssignment extends Model
{
    /** @use HasFactory<BlockAssignmentFactory> */
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
     * @return HasOne<BlockAssignmentTest, $this>
     */
    public function test(): HasOne
    {
        return $this->hasOne(BlockAssignmentTest::class);
    }
}
