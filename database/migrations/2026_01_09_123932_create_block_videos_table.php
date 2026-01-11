<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('block_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id')->unique()->constrained('lesson_blocks')->cascadeOnDelete();
            $table->string('url');
            $table->unsignedInteger('duration')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('block_videos');
    }
};
