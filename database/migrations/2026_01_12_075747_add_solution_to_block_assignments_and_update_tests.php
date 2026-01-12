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
        Schema::table('block_assignments', function (Blueprint $table) {
            $table->text('solution')->nullable()->after('starter_code');
        });

        Schema::table('block_assignment_tests', function (Blueprint $table) {
            $table->text('stdin')->nullable()->after('block_assignment_id');
            $table->text('expected_output')->nullable()->after('stdin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('block_assignments', function (Blueprint $table) {
            $table->dropColumn('solution');
        });

        Schema::table('block_assignment_tests', function (Blueprint $table) {
            $table->dropColumn(['stdin', 'expected_output']);
        });
    }
};
