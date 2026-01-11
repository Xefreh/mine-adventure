<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First rename the foreign key column in block_task_tests
        Schema::table('block_task_tests', function (Blueprint $table) {
            $table->renameColumn('block_task_id', 'block_assignment_id');
        });

        // Rename the tables
        Schema::rename('block_tasks', 'block_assignments');
        Schema::rename('block_task_tests', 'block_assignment_tests');

        // Update the type value in lesson_blocks
        DB::table('lesson_blocks')->where('type', 'task')->update(['type' => 'assignment']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update the type value back
        DB::table('lesson_blocks')->where('type', 'assignment')->update(['type' => 'task']);

        // Rename tables back
        Schema::rename('block_assignments', 'block_tasks');
        Schema::rename('block_assignment_tests', 'block_task_tests');

        // Rename the column back
        Schema::table('block_task_tests', function (Blueprint $table) {
            $table->renameColumn('block_assignment_id', 'block_task_id');
        });
    }
};
