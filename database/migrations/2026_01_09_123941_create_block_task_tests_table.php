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
        Schema::create('block_task_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_task_id')->constrained()->cascadeOnDelete();
            $table->text('file_content');
            $table->string('class_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('block_task_tests');
    }
};
