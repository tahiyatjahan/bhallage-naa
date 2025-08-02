<?php

namespace App\Http\Controllers;

use App\Models\DailyPrompt;
use App\Models\MoodJournal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DailyPromptController extends Controller
{
    /**
     * Show today's prompt and allow users to write a mood journal
     */
    public function showToday()
    {
        // Get or create today's prompt
        $todayPrompt = DailyPrompt::createTodayPrompt();
        
        // Check if user has already written a journal for today
        $userJournal = null;
        if (Auth::check()) {
            $userJournal = MoodJournal::where('user_id', Auth::id())
                                     ->whereDate('created_at', Carbon::today())
                                     ->first();
        }

        return view('daily-prompt.show', compact('todayPrompt', 'userJournal'));
    }

    /**
     * Show a specific date's prompt
     */
    public function showForDate($date)
    {
        $prompt = DailyPrompt::getPromptForDate($date);
        
        if (!$prompt) {
            return redirect()->route('daily-prompt.today')
                           ->with('error', 'No prompt found for that date.');
        }

        $userJournal = null;
        if (Auth::check()) {
            $userJournal = MoodJournal::where('user_id', Auth::id())
                                     ->whereDate('created_at', $date)
                                     ->first();
        }

        return view('daily-prompt.show', compact('prompt', 'userJournal'));
    }

    /**
     * Show recent prompts
     */
    public function showRecent()
    {
        $recentPrompts = DailyPrompt::getRecentPrompts(14); // Last 2 weeks
        
        return view('daily-prompt.recent', compact('recentPrompts'));
    }

    /**
     * Admin: Show all prompts management
     */
    public function adminIndex()
    {
        $prompts = DailyPrompt::orderBy('prompt_date', 'desc')->paginate(15);
        
        return view('admin.daily-prompts.index', compact('prompts'));
    }

    /**
     * Admin: Create a new prompt
     */
    public function adminCreate()
    {
        return view('admin.daily-prompts.create');
    }

    /**
     * Admin: Store a new prompt
     */
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'prompt' => 'required|string|max:500',
            'category' => 'required|string|in:general,gratitude,reflection,goals,self-care,relationships',
            'prompt_date' => 'required|date|unique:daily_prompts,prompt_date',
        ]);

        DailyPrompt::create([
            'prompt' => $validated['prompt'],
            'category' => $validated['category'],
            'prompt_date' => $validated['prompt_date'],
            'is_active' => true,
        ]);

        return redirect()->route('admin.daily-prompts.index')
                        ->with('status', 'Daily prompt created successfully!');
    }

    /**
     * Admin: Edit a prompt
     */
    public function adminEdit($id)
    {
        $prompt = DailyPrompt::findOrFail($id);
        
        return view('admin.daily-prompts.edit', compact('prompt'));
    }

    /**
     * Admin: Update a prompt
     */
    public function adminUpdate(Request $request, $id)
    {
        $prompt = DailyPrompt::findOrFail($id);
        
        $validated = $request->validate([
            'prompt' => 'required|string|max:500',
            'category' => 'required|string|in:general,gratitude,reflection,goals,self-care,relationships',
            'prompt_date' => 'required|date|unique:daily_prompts,prompt_date,' . $id,
        ]);

        $prompt->update([
            'prompt' => $validated['prompt'],
            'category' => $validated['category'],
            'prompt_date' => $validated['prompt_date'],
        ]);

        return redirect()->route('admin.daily-prompts.index')
                        ->with('status', 'Daily prompt updated successfully!');
    }

    /**
     * Admin: Toggle prompt active status
     */
    public function adminToggleStatus($id)
    {
        $prompt = DailyPrompt::findOrFail($id);
        $prompt->is_active = !$prompt->is_active;
        $prompt->save();

        $status = $prompt->is_active ? 'activated' : 'deactivated';
        return back()->with('status', "Daily prompt has been {$status}.");
    }

    /**
     * Admin: Delete a prompt
     */
    public function adminDestroy($id)
    {
        $prompt = DailyPrompt::findOrFail($id);
        $prompt->delete();

        return back()->with('status', 'Daily prompt deleted successfully.');
    }

    /**
     * Admin: Generate prompt for today
     */
    public function adminGenerateToday()
    {
        $prompt = DailyPrompt::createTodayPrompt();
        
        return back()->with('status', 'Today\'s prompt has been generated: "' . $prompt->prompt . '"');
    }
} 