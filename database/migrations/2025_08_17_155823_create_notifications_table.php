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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // User receiving the notification
            $table->unsignedBigInteger('from_user_id')->nullable(); // User who triggered the notification
            $table->string('type'); // Type of notification (comment, like, reply, report, etc.)
            $table->string('title'); // Notification title
            $table->text('message'); // Notification message
            $table->string('notifiable_type'); // Model type (MoodJournal, CreativePost, Whisper, etc.)
            $table->unsignedBigInteger('notifiable_id'); // ID of the related model
            $table->string('action_url')->nullable(); // URL to navigate to when clicked
            $table->json('metadata')->nullable(); // Additional data in JSON format
            $table->boolean('is_read')->default(false); // Whether notification has been read
            $table->timestamp('read_at')->nullable(); // When notification was read
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'is_read']);
            $table->index(['notifiable_type', 'notifiable_id']);
            $table->index(['type', 'created_at']);
            
            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('from_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
