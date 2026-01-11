<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockText extends Model
{
    /** @use HasFactory<\Database\Factories\BlockTextFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'block_id',
        'content',
    ];

    /**
     * @return BelongsTo<LessonBlock, $this>
     */
    public function block(): BelongsTo
    {
        return $this->belongsTo(LessonBlock::class, 'block_id');
    }
}
