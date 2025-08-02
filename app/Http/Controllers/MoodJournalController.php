<?php

namespace App\Http\Controllers;

use App\Models\MoodJournal;
use App\Models\MoodJournalComment;
use App\Models\MoodJournalUpvote;
use App\Models\DailyPrompt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MoodJournalController extends Controller
{
    public function index(Request $request)
    {
        $query = MoodJournal::with(['user', 'comments', 'upvotes'])->latest();
        
        // Filter by hashtag if provided
        if ($request->has('hashtag') && $request->hashtag) {
            $query->withHashtag($request->hashtag);
        }
        
        $journals = $query->paginate(10);
        $predefinedHashtags = MoodJournal::getPredefinedHashtags();
        
        // Get today's daily prompt
        $todayPrompt = DailyPrompt::getTodayPrompt();
        
        return view('mood-journal.index', compact('journals', 'predefinedHashtags', 'todayPrompt'));
    }

    public function create()
    {
        $predefinedHashtags = MoodJournal::getPredefinedHashtags();
        
        // Get today's daily prompt
        $todayPrompt = DailyPrompt::getTodayPrompt();
        
        return view('mood-journal.create', compact('predefinedHashtags', 'todayPrompt'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'hashtags' => 'nullable|array',
            'hashtags.*' => 'string|max:50',
            'mood_rating' => 'nullable|integer|min:1|max:10'
        ]);

        $hashtags = $validated['hashtags'] ?? [];
        
        // Clean hashtags (remove # if present and convert to lowercase)
        $cleanedHashtags = array_map(function($tag) {
            return strtolower(ltrim($tag, '#'));
        }, $hashtags);

        $journal = MoodJournal::create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'hashtags' => $cleanedHashtags,
            'mood_rating' => $validated['mood_rating'] ?? null
        ]);

        return redirect()->route('mood_journal.show', $journal->id)
                        ->with('status', 'Journal entry created successfully!');
    }

    public function show($id)
    {
        $journal = MoodJournal::with(['user', 'comments.user', 'upvotes'])->findOrFail($id);
        $predefinedHashtags = MoodJournal::getPredefinedHashtags();
        
        // Get today's daily prompt
        $todayPrompt = DailyPrompt::getTodayPrompt();
        
        return view('mood-journal.show', compact('journal', 'predefinedHashtags', 'todayPrompt'));
    }

    public function upvote($id)
    {
        $journal = MoodJournal::findOrFail($id);
        
        // Check if user already upvoted
        $existingUpvote = MoodJournalUpvote::where('user_id', Auth::id())
                                          ->where('mood_journal_id', $id)
                                          ->first();
        
        if ($existingUpvote) {
            $existingUpvote->delete();
            $message = 'Upvote removed.';
        } else {
            MoodJournalUpvote::create([
                'user_id' => Auth::id(),
                'mood_journal_id' => $id
            ]);
            $message = 'Journal upvoted!';
        }
        
        return back()->with('status', $message);
    }

    public function comment(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:500'
        ]);

        MoodJournalComment::create([
            'user_id' => Auth::id(),
            'mood_journal_id' => $id,
            'content' => $validated['content']
        ]);

        return back()->with('status', 'Comment added successfully!');
    }

    public function deleteComment($id)
    {
        $comment = MoodJournalComment::findOrFail($id);
        
        // Check if user owns the comment or is admin
        if ($comment->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return back()->with('error', 'Unauthorized action.');
        }
        
        $comment->delete();
        return back()->with('status', 'Comment deleted successfully.');
    }

    public function editComment($id)
    {
        $comment = MoodJournalComment::findOrFail($id);
        
        if ($comment->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }
        
        return view('mood-journal.edit-comment', compact('comment'));
    }

    public function updateComment(Request $request, $id)
    {
        $comment = MoodJournalComment::findOrFail($id);
        
        if ($comment->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'content' => 'required|string|max:500'
        ]);
        
        $comment->update(['content' => $validated['content']]);
        
        return redirect()->route('mood_journal.show', $comment->mood_journal_id)
                        ->with('status', 'Comment updated successfully!');
    }

    /**
     * Filter journals by hashtag
     */
    public function filterByHashtag($hashtag)
    {
        $journals = MoodJournal::with(['user', 'comments', 'upvotes'])
                              ->withHashtag($hashtag)
                              ->latest()
                              ->paginate(10);
        
        $predefinedHashtags = MoodJournal::getPredefinedHashtags();
        
        // Get today's daily prompt
        $todayPrompt = DailyPrompt::getTodayPrompt();
        
        return view('mood-journal.index', compact('journals', 'predefinedHashtags', 'hashtag', 'todayPrompt'));
    }
}
