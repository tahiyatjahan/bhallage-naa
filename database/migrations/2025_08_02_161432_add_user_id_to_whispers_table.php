<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing whispers to have a valid user_id (first user)
        $firstUserId = DB::table('users')->value('id');
        if ($firstUserId) {
            DB::table('whispers')->where('user_id', 0)->orWhereNull('user_id')->update(['user_id' => $firstUserId]);
        }

        // Add the foreign key constraint
        Schema::table('whispers', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whispers', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }
};
