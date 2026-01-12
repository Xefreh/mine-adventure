<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockAssignmentTest extends Model
{
    /** @use HasFactory<\Database\Factories\BlockAssignmentTestFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'block_assignment_id',
        'stdin',
        'expected_output',
        'file_content',
        'class_name',
    ];

    /**
     * @return BelongsTo<BlockAssignment, $this>
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(BlockAssignment::class, 'block_assignment_id');
    }
}
