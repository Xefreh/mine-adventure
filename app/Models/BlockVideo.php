<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockVideo extends Model
{
    /** @use HasFactory<\Database\Factories\BlockVideoFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'block_id',
        'url',
        'duration',
    ];

    /**
     * @return BelongsTo<LessonBlock, $this>
     */
    public function block(): BelongsTo
    {
        return $this->belongsTo(LessonBlock::class, 'block_id');
    }
}
