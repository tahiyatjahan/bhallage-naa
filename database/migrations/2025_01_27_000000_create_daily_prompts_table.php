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
        Schema::create('daily_prompts', function (Blueprint $table) {
            $table->id();
            $table->text('prompt');
            $table->string('category')->default('general'); // general, reflection, gratitude, goals, etc.
            $table->date('prompt_date')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_prompts');
    }
}; 