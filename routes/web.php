<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SecretWhisperController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MoodJournalController;
use App\Http\Controllers\DailyPromptController;
use App\Http\Controllers\CreativePostController;

Route::get('/welcome', function () {
    return view('welcome_landing');
})->name('welcome');

Route::get('/', function () {
    return Auth::check() ? redirect('/home') : redirect('/welcome');
});

Route::get('/home', function () {
    return view('home');
})->middleware(['auth'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Daily Prompts
    Route::get('/daily-prompt', [DailyPromptController::class, 'showToday'])->name('daily-prompt.today');
    Route::get('/daily-prompt/recent', [DailyPromptController::class, 'showRecent'])->name('daily-prompt.recent');
    Route::get('/daily-prompt/{date}', [DailyPromptController::class, 'showForDate'])->name('daily-prompt.date');

    // Secret Whispers
    Route::get('/whispers', [SecretWhisperController::class, 'index'])->name('whispers.index');
    Route::get('/whispers/create', [SecretWhisperController::class, 'create'])->name('whispers.create');
    Route::post('/whispers', [SecretWhisperController::class, 'store'])->name('whispers.store');
    Route::get('/whispers/{id}/report', [SecretWhisperController::class, 'report'])->name('whispers.report');
    Route::post('/whispers/{id}/report', [SecretWhisperController::class, 'storeReport'])->name('whispers.report.store');

    // Mood Journal
    Route::get('/mood-journal', [MoodJournalController::class, 'index'])->name('mood_journal.index');
    Route::get('/mood-journal/create', [MoodJournalController::class, 'create'])->name('mood_journal.create');
    Route::post('/mood-journal', [MoodJournalController::class, 'store'])->name('mood_journal.store');
    Route::get('/mood-journal/{id}/edit', [MoodJournalController::class, 'edit'])->name('mood_journal.edit');
    Route::patch('/mood-journal/{id}', [MoodJournalController::class, 'update'])->name('mood_journal.update');
    Route::delete('/mood-journal/{id}', [MoodJournalController::class, 'destroy'])->name('mood_journal.destroy');
    Route::get('/mood-journal/hashtag/{hashtag}', [MoodJournalController::class, 'filterByHashtag'])->name('mood_journal.hashtag');
    Route::post('/mood-journal/{id}/upvote', [MoodJournalController::class, 'upvote'])->name('mood_journal.upvote');
    Route::post('/mood-journal/{id}/comment', [MoodJournalController::class, 'comment'])->name('mood_journal.comment');
    Route::get('/mood-journal/{id}', [MoodJournalController::class, 'show'])->name('mood_journal.show');
    Route::delete('/mood-journal/comment/{id}', [MoodJournalController::class, 'deleteComment'])->name('mood_journal.comment.delete');
    Route::get('/mood-journal/comment/{id}/edit', [MoodJournalController::class, 'editComment'])->name('mood_journal.comment.edit');
    Route::patch('/mood-journal/comment/{id}', [MoodJournalController::class, 'updateComment'])->name('mood_journal.comment.update');

    // Express Yourself - Creative Posts
    Route::get('/express-yourself', [CreativePostController::class, 'index'])->name('creative-posts.index');
    Route::get('/express-yourself/create', [CreativePostController::class, 'create'])->name('creative-posts.create');
    Route::post('/express-yourself', [CreativePostController::class, 'store'])->name('creative-posts.store');
    Route::get('/express-yourself/category/{category}', [CreativePostController::class, 'filterByCategory'])->name('creative-posts.category');
    Route::get('/express-yourself/{id}', [CreativePostController::class, 'show'])->name('creative-posts.show');
    Route::get('/express-yourself/{id}/edit', [CreativePostController::class, 'edit'])->name('creative-posts.edit');
    Route::patch('/express-yourself/{id}', [CreativePostController::class, 'update'])->name('creative-posts.update');
    Route::delete('/express-yourself/{id}', [CreativePostController::class, 'destroy'])->name('creative-posts.destroy');
    Route::post('/express-yourself/{id}/like', [CreativePostController::class, 'like'])->name('creative-posts.like');
    Route::post('/express-yourself/{id}/comment', [CreativePostController::class, 'comment'])->name('creative-posts.comment');
    Route::delete('/express-yourself/comment/{id}', [CreativePostController::class, 'deleteComment'])->name('creative-posts.comment.delete');
});

Route::middleware(['auth', 'admin'])->group(function () {
    // Admin Dashboard
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Admin Profile Management
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::patch('/admin/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    
    // User Management
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::patch('/admin/users/{id}/toggle-admin', [AdminController::class, 'toggleAdminStatus'])->name('admin.users.toggle-admin');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    
    // Reports Management
    Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
    
    // Daily Prompts Management
    Route::get('/admin/daily-prompts', [DailyPromptController::class, 'adminIndex'])->name('admin.daily-prompts.index');
    Route::get('/admin/daily-prompts/create', [DailyPromptController::class, 'adminCreate'])->name('admin.daily-prompts.create');
    Route::post('/admin/daily-prompts', [DailyPromptController::class, 'adminStore'])->name('admin.daily-prompts.store');
    Route::get('/admin/daily-prompts/{id}/edit', [DailyPromptController::class, 'adminEdit'])->name('admin.daily-prompts.edit');
    Route::patch('/admin/daily-prompts/{id}', [DailyPromptController::class, 'adminUpdate'])->name('admin.daily-prompts.update');
    Route::patch('/admin/daily-prompts/{id}/toggle', [DailyPromptController::class, 'adminToggleStatus'])->name('admin.daily-prompts.toggle');
    Route::delete('/admin/daily-prompts/{id}', [DailyPromptController::class, 'adminDestroy'])->name('admin.daily-prompts.destroy');
    Route::post('/admin/daily-prompts/generate-today', [DailyPromptController::class, 'adminGenerateToday'])->name('admin.daily-prompts.generate-today');
    
    // Creative Posts Management
    Route::get('/admin/creative-posts', [CreativePostController::class, 'adminIndex'])->name('admin.creative-posts.index');
    Route::patch('/admin/creative-posts/{id}/toggle-featured', [CreativePostController::class, 'adminToggleFeatured'])->name('admin.creative-posts.toggle-featured');
    Route::delete('/admin/creative-posts/{id}', [CreativePostController::class, 'adminDestroy'])->name('admin.creative-posts.destroy');
    
    // Statistics
    Route::get('/admin/statistics', [AdminController::class, 'statistics'])->name('admin.statistics');
    
    // Whisper Management
    Route::delete('/admin/whispers/{id}', [AdminController::class, 'deleteWhisper'])->name('admin.whispers.delete');
});

require __DIR__.'/auth.php';
