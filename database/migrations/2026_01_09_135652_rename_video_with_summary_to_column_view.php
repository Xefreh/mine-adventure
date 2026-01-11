<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('lessons')
            ->where('layout', 'video_with_summary')
            ->update(['layout' => 'column_view']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('lessons')
            ->where('layout', 'column_view')
            ->update(['layout' => 'video_with_summary']);
    }
};
