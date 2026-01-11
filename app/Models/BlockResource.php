<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockResource extends Model
{
    /** @use HasFactory<\Database\Factories\BlockResourceFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'block_id',
        'links',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'links' => 'array',
        ];
    }

    /**
     * @return BelongsTo<LessonBlock, $this>
     */
    public function block(): BelongsTo
    {
        return $this->belongsTo(LessonBlock::class, 'block_id');
    }
}
