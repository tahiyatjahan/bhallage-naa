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
            $table->integer('mood_rating')->nullable()->after('hashtags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mood_journals', function (Blueprint $table) {
            $table->dropColumn('mood_rating');
        });
    }
}; 