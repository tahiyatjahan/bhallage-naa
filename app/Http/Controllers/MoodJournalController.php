<?php

namespace App\Http\Controllers;

use App\Models\MoodJournal;
use App\Models\MoodJournalComment;
use App\Models\MoodJournalUpvote;
use App\Models\DailyPrompt;
use App\Models\CommentReply;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MoodJournalController extends Controller
{
    public function index(Request $request)
    {
        $query = MoodJournal::with(['user', 'comments', 'upvotes', 'dailyPrompt'])->latest();
        
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

    public function createWithPrompt($promptId = null)
    {
        $predefinedHashtags = MoodJournal::getPredefinedHashtags();
        
        // Get the specific prompt or today's prompt
        if ($promptId) {
            $prompt = DailyPrompt::findOrFail($promptId);
        } else {
            $prompt = DailyPrompt::getTodayPrompt();
        }
        
        return view('mood-journal.create-with-prompt', compact('predefinedHashtags', 'prompt'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'hashtags' => 'nullable|array',
            'hashtags.*' => 'string|max:50',
            'mood_rating' => 'nullable|integer|min:1|max:10',
            'daily_prompt_id' => 'nullable|exists:daily_prompts,id'
        ]);

        $hashtags = $validated['hashtags'] ?? [];
        
        // Clean hashtags (remove # if present and convert to lowercase)
        $cleanedHashtags = array_map(function($tag) {
            return strtolower(ltrim($tag, '#'));
        }, $hashtags);

        $journal = MoodJournal::create([
            'user_id' => Auth::id(),
            'daily_prompt_id' => $validated['daily_prompt_id'] ?? null,
            'content' => $validated['content'],
            'hashtags' => $cleanedHashtags,
            'mood_rating' => $validated['mood_rating'] ?? null
        ]);

        // Check for triggering keywords and create support report if needed
        $detectedKeywords = \App\Models\SupportReport::detectKeywords($validated['content']);
        
        if (!empty($detectedKeywords)) {
            $supportMessage = \App\Models\SupportReport::generateSupportMessage($detectedKeywords);
            $supportResources = \App\Models\SupportReport::getSupportResources($detectedKeywords);
            
            \App\Models\SupportReport::create([
                'user_id' => Auth::id(),
                'mood_journal_id' => $journal->id,
                'keywords_detected' => $detectedKeywords,
                'support_resources' => $supportResources,
                'message' => $supportMessage
            ]);

            // Create notification for all admins about the support report
            NotificationService::report(
                reportableType: 'MoodJournal',
                reportableId: $journal->id,
                reportReason: 'Triggering keywords detected: ' . implode(', ', $detectedKeywords),
                actionUrl: route('support-reports.index')
            );
        }

        return redirect()->route('mood_journal.show', $journal->id)
                        ->with('status', 'Journal entry created successfully!');
    }

    public function show($id)
    {
        $journal = MoodJournal::with(['user', 'comments.user', 'comments.replies.user', 'upvotes.user', 'dailyPrompt'])->findOrFail($id);
        $predefinedHashtags = MoodJournal::getPredefinedHashtags();
        
        // Get today's daily prompt
        $todayPrompt = DailyPrompt::getTodayPrompt();
        
        return view('mood-journal.show', compact('journal', 'predefinedHashtags', 'todayPrompt'));
    }

    public function edit($id)
    {
        $journal = MoodJournal::findOrFail($id);
        
        // Check if user owns this journal entry
        if ($journal->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own journal entries.');
        }
        
        $predefinedHashtags = MoodJournal::getPredefinedHashtags();
        
        return view('mood-journal.edit', compact('journal', 'predefinedHashtags'));
    }

    public function update(Request $request, $id)
    {
        $journal = MoodJournal::findOrFail($id);
        
        // Check if user owns this journal entry
        if ($journal->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own journal entries.');
        }

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

        $journal->update([
            'content' => $validated['content'],
            'hashtags' => $cleanedHashtags,
            'mood_rating' => $validated['mood_rating'] ?? null
        ]);

        return redirect()->route('mood_journal.show', $journal->id)
                        ->with('status', 'Journal entry updated successfully!');
    }

    public function destroy($id)
    {
        $journal = MoodJournal::findOrFail($id);
        
        // Check if user owns this journal entry
        if ($journal->user_id !== Auth::id()) {
            abort(403, 'You can only delete your own journal entries.');
        }

        $journal->delete();

        return redirect()->route('mood_journal.index')
                        ->with('status', 'Journal entry deleted successfully!');
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
            $upvoted = false;
        } else {
            MoodJournalUpvote::create([
                'user_id' => Auth::id(),
                'mood_journal_id' => $id
            ]);
            $upvoted = true;
            
            // Create notification for journal owner
            NotificationService::like(
                postOwnerId: $journal->user_id,
                likerId: Auth::id(),
                postType: 'MoodJournal',
                postId: $journal->id,
                postTitle: Str::limit($journal->content, 50),
                actionUrl: route('mood_journal.show', $journal->id)
            );
        }
        
        // Return JSON response for AJAX requests
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'upvoted' => $upvoted,
                'upvotes' => $journal->upvotes()->count()
            ]);
        }
        
        return back();
    }

    public function comment(Request $request, $id)
    {
        $journal = MoodJournal::findOrFail($id);
        
        $validated = $request->validate([
            'content' => 'required|string|max:500'
        ]);

        $comment = MoodJournalComment::create([
            'user_id' => Auth::id(),
            'mood_journal_id' => $id,
            'content' => $validated['content']
        ]);

        // Create notification for journal owner
        NotificationService::comment(
            postOwnerId: $journal->user_id,
            commenterId: Auth::id(),
            postType: 'MoodJournal',
            postId: $journal->id,
            postTitle: Str::limit($journal->content, 50),
            actionUrl: route('mood_journal.show', $journal->id)
        );

        // Return JSON response for AJAX requests
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully!',
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user' => [
                        'name' => $comment->user->name
                    ]
                ],
                'commentCount' => MoodJournalComment::where('mood_journal_id', $id)->count()
            ]);
        }

        return back()->with('status', 'Comment added successfully!');
    }

    public function deleteComment($id)
    {
        $comment = MoodJournalComment::findOrFail($id);
        
        // Check if user owns the comment or is admin
        if ($comment->user_id !== Auth::id() && !Auth::user()->is_admin) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }
            return back()->with('error', 'Unauthorized action.');
        }
        
        $journalId = $comment->mood_journal_id;
        $comment->delete();
        
        // Return JSON response for AJAX requests
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully.',
                'journalId' => $journalId,
                'commentCount' => MoodJournalComment::where('mood_journal_id', $journalId)->count()
            ]);
        }
        
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

    /**
     * Reply to a comment
     */
    public function replyToComment(Request $request, $id)
    {
        $comment = MoodJournalComment::findOrFail($id);
        
        $validated = $request->validate([
            'content' => 'required|string|max:500'
        ]);

        $reply = CommentReply::create([
            'user_id' => Auth::id(),
            'comment_id' => $id,
            'comment_type' => 'mood_journal',
            'content' => $validated['content']
        ]);

        // Create notification for comment owner
        NotificationService::reply(
            commentOwnerId: $comment->user_id,
            replierId: Auth::id(),
            postType: 'MoodJournal',
            postId: $comment->mood_journal_id,
            postTitle: Str::limit($comment->moodJournal->content, 50),
            actionUrl: route('mood_journal.show', $comment->mood_journal_id)
        );

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Reply added successfully!',
                'reply' => [
                    'id' => $reply->id,
                    'content' => $reply->content,
                    'user' => [
                        'name' => $reply->user->name,
                        'profile_picture' => $reply->user->profile_picture
                    ]
                ]
            ]);
        }

        return back()->with('status', 'Reply added successfully!');
    }

    /**
     * Delete a reply
     */
    public function deleteReply($id)
    {
        $reply = CommentReply::findOrFail($id);
        
        if ($reply->user_id !== Auth::id() && !Auth::user()->is_admin) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }
            return back()->with('error', 'Unauthorized action.');
        }
        
        $reply->delete();
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Reply deleted successfully.'
            ]);
        }
        
        return back()->with('status', 'Reply deleted successfully.');
    }

    /**
     * Edit a reply
     */
    public function editReply($id)
    {
        $reply = CommentReply::findOrFail($id);
        
        if ($reply->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }
        
        return view('mood-journal.edit-reply', compact('reply'));
    }

    /**
     * Update a reply
     */
    public function updateReply(Request $request, $id)
    {
        $reply = CommentReply::findOrFail($id);
        
        if ($reply->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'content' => 'required|string|max:500'
        ]);
        
        $reply->update(['content' => $validated['content']]);
        
        return back()->with('status', 'Reply updated successfully!');
    }
}
