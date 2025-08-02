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
        Schema::table('mood_journals', function (Blueprint $table) {
            $table->foreignId('daily_prompt_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mood_journals', function (Blueprint $table) {
            $table->dropForeign(['daily_prompt_id']);
            $table->dropColumn('daily_prompt_id');
        });
    }
};
