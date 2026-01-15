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
        Schema::table('block_assignment_tests', function (Blueprint $table) {
            $table->dropColumn(['stdin', 'expected_output']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('block_assignment_tests', function (Blueprint $table) {
            $table->text('stdin')->nullable();
            $table->text('expected_output')->nullable();
        });
    }
};
