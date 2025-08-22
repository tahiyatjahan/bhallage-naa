@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('mood_journal.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Journals
            </a>
        </div>

        <!-- Daily Prompt Section -->
        @if($todayPrompt)
            <div class="mb-8 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-3">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h2 class="text-lg font-semibold text-gray-900">Today's Daily Prompt</h2>
                            <span class="ml-3 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $todayPrompt->category_color }} text-white">
                                {{ $todayPrompt->category_display_name }}
                            </span>
                        </div>
                        <p class="text-gray-800 mb-3 leading-relaxed">{{ $todayPrompt->prompt }}</p>
                        <a href="{{ route('mood_journal.create-with-prompt') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                            Write your own entry →
                        </a>
                    </div>
                    <div class="ml-6 text-right">
                        <p class="text-sm text-gray-500">{{ $todayPrompt->prompt_date->format('l, F j, Y') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Journal Entry -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-center space-x-4">
                    @if($journal->user->profile_picture)
                        <img src="/{{ $journal->user->profile_picture }}" alt="Profile Picture" class="w-12 h-12 rounded-full object-cover">
                    @else
                        <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                            <span class="text-gray-600 font-semibold text-lg">{{ substr($journal->user->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $journal->user->name }}'s Journal Entry</h1>
                        <p class="text-gray-500">{{ $journal->created_at->format('l, F j, Y \a\t g:i A') }}</p>
                    </div>
                </div>
                @if($journal->mood_rating)
                    <div class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Mood Rating: {{ $journal->mood_rating }}/10
                        </span>
                    </div>
                @endif
            </div>

            <!-- Hashtags -->
            @if($journal->hashtags_array && count($journal->hashtags_array) > 0)
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Tags:</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($journal->hashtags_array as $hashtag)
                            <a href="{{ route('mood_journal.hashtag', $hashtag) }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $journal->getHashtagColorClass($hashtag) }} hover:opacity-80 transition-opacity">
                                #{{ ucfirst($hashtag) }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Daily Prompt Info -->
            @if($journal->daily_prompt_id && $journal->dailyPrompt)
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                    <div class="flex items-center space-x-2 mb-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-blue-800">Responding to Daily Prompt</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $journal->dailyPrompt->category_color }} text-white">
                            {{ $journal->dailyPrompt->category_display_name }}
                        </span>
                    </div>
                    <p class="text-blue-700 italic mb-2">"{{ $journal->dailyPrompt->prompt }}"</p>
                    <p class="text-xs text-blue-600">{{ $journal->dailyPrompt->prompt_date->format('l, F j, Y') }}</p>
                </div>
            @endif

            <!-- Content -->
            <div class="mb-6">
                <p class="text-gray-800 text-lg leading-relaxed whitespace-pre-wrap">{{ $journal->content }}</p>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <div class="flex items-center space-x-6">
                    @php
                        $hasUpvoted = \App\Models\MoodJournalUpvote::where('user_id', Auth::id())
                            ->where('mood_journal_id', $journal->id)
                            ->exists();
                    @endphp
                    <button type="button" onclick="upvoteJournal({{ $journal->id }})" 
                            class="flex items-center space-x-3 {{ $hasUpvoted ? 'text-red-500' : 'text-gray-500' }} hover:text-red-500 transition-all duration-200 group" 
                            id="upvote-btn-{{ $journal->id }}">
                        <span class="text-3xl group-hover:scale-110 transition-transform" id="upvote-emoji-{{ $journal->id }}">
                            {{ $hasUpvoted ? '❤️' : '🤍' }}
                        </span>
                        <span class="text-lg font-semibold" id="upvote-count-{{ $journal->id }}">{{ $journal->upvotes->count() }} likes</span>
                    </button>
                    <div class="flex items-center space-x-3 text-gray-500">
                        <span class="text-3xl">💬</span>
                        <span class="text-lg font-semibold" id="comment-count-{{ $journal->id }}">{{ $journal->comments->count() }} comments</span>
                    </div>
                </div>
                @if(Auth::id() === $journal->user_id)
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('mood_journal.edit', $journal->id) }}" class="inline-flex items-center px-4 py-2 border border-yellow-300 rounded-md font-medium text-yellow-700 bg-yellow-50 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Entry
                        </a>
                        <form action="{{ route('mood_journal.destroy', $journal->id) }}" method="POST" class="inline" 
                              onsubmit="return confirm('Are you sure you want to delete this journal entry? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md font-medium text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Entry
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- Comments Section -->
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20">
            <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 px-8 py-6 text-white">
                <h3 class="text-2xl font-bold text-center">💬 Community Discussion</h3>
            </div>

            <!-- Add Comment Form -->
            <div class="p-8 border-b border-gray-100">
                <form id="comment-form-{{ $journal->id }}">
                    @csrf
                    <div class="space-y-4">
                        <label for="comment-content-{{ $journal->id }}" class="block text-lg font-semibold text-gray-800">
                            Share your thoughts about this journal entry:
                        </label>
                        <textarea name="content" id="comment-content-{{ $journal->id }}" rows="4" required
                                  class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-yellow-200 focus:border-yellow-500 text-lg transition-all duration-200 resize-none"
                                  placeholder="What do you think about this journal entry? Share your thoughts..."></textarea>
                        <div id="comment-error-{{ $journal->id }}" class="mt-1 text-sm text-red-600 hidden"></div>
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="bg-gradient-to-r from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-white px-8 py-3 rounded-2xl font-semibold text-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                                💭 Share Comment
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Comments List -->
            <div class="p-8">
                <div id="comments-container-{{ $journal->id }}" class="space-y-6">
                    @forelse($journal->comments as $comment)
                        <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl p-6 border border-gray-100" id="comment-{{ $comment->id }}">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    @if($comment->user->profile_picture)
                                        <img src="/{{ $comment->user->profile_picture }}" 
                                             alt="{{ $comment->user->name }}'s profile picture"
                                             class="w-12 h-12 rounded-full object-cover border-2 border-yellow-200 shadow-md">
                                    @else
                                        <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center text-white font-semibold text-lg shadow-md">
                                            {{ substr($comment->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="font-semibold text-gray-900 text-lg">{{ $comment->user->name }}</span>
                                        <span class="text-sm text-gray-500 flex items-center">
                                            <span class="mr-2">🕐</span>
                                            {{ $comment->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="text-gray-700 text-lg leading-relaxed">{{ $comment->content }}</p>
                                    
                                    <!-- Comment Actions -->
                                    <div class="mt-4 flex items-center justify-between">
                                        <div class="flex space-x-4">
                                            <button type="button" 
                                                    onclick="toggleReplyForm({{ $comment->id }})"
                                                    class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                                <span class="mr-2">💬</span>
                                                Reply
                                            </button>
                                            @if($comment->user_id === Auth::id())
                                                <a href="{{ route('mood_journal.comment.edit', $comment->id) }}" 
                                                   class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                                    <span class="mr-2">✏️</span>
                                                    Edit
                                                </a>
                                                <button type="button" 
                                                        onclick="deleteComment({{ $comment->id }})"
                                                        class="text-red-600 hover:text-red-800 font-medium flex items-center">
                                                    <span class="mr-2">🗑️</span>
                                                    Delete
                                                </button>
                                            @elseif(Auth::user()->is_admin)
                                                <button type="button" 
                                                        onclick="deleteComment({{ $comment->id }})"
                                                        class="text-red-600 hover:text-red-800 font-medium flex items-center">
                                                    <span class="mr-2">🗑️</span>
                                                    Delete
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Reply Form (Hidden by default) -->
                                    <div id="reply-form-{{ $comment->id }}" class="mt-4 hidden">
                                        <form onsubmit="addReply({{ $comment->id }}); return false;" class="space-y-3">
                                            @csrf
                                            <textarea name="content" rows="2" required
                                                      class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-sm transition-all duration-200 resize-none"
                                                      placeholder="Write your reply..."></textarea>
                                            <div class="flex justify-end space-x-2">
                                                <button type="button" 
                                                        onclick="toggleReplyForm({{ $comment->id }})"
                                                        class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium">
                                                    Cancel
                                                </button>
                                                <button type="submit" 
                                                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                                    Reply
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Replies Section -->
                                    @if($comment->replies->count() > 0)
                                        <div class="mt-4 ml-8 space-y-3">
                                            <div class="text-sm text-gray-500 font-medium mb-2">💬 Replies:</div>
                                            @foreach($comment->replies as $reply)
                                                <div class="bg-white rounded-lg p-4 border border-gray-200" id="reply-{{ $reply->id }}">
                                                    <div class="flex items-start space-x-3">
                                                        <div class="flex-shrink-0">
                                                            @if($reply->user->profile_picture)
                                                                <img src="/{{ $reply->user->profile_picture }}" 
                                                                     alt="{{ $reply->user->name }}'s profile picture"
                                                                     class="w-8 h-8 rounded-full object-cover border border-gray-200">
                                                            @else
                                                                <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                                                    {{ substr($reply->user->name, 0, 1) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="flex items-center justify-between mb-2">
                                                                <span class="font-medium text-gray-900 text-sm">{{ $reply->user->name }}</span>
                                                                <span class="text-xs text-gray-500">
                                                                    {{ $reply->created_at->diffForHumans() }}
                                                                </span>
                                                            </div>
                                                            <p class="text-gray-700 text-sm">{{ $reply->content }}</p>
                                                            
                                                            <!-- Reply Actions -->
                                                            @if($reply->user_id === Auth::id())
                                                                <div class="mt-2 flex space-x-3">
                                                                    <a href="{{ route('mood_journal.reply.edit', $reply->id) }}" 
                                                                       class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                                                        Edit
                                                                    </a>
                                                                    <button type="button" 
                                                                            onclick="deleteReply({{ $reply->id }})"
                                                                            class="text-red-600 hover:text-red-800 text-xs font-medium">
                                                                        Delete
                                                                    </button>
                                                                </div>
                                                            @elseif(Auth::user()->is_admin)
                                                                <div class="mt-2">
                                                                    <button type="button" 
                                                                            onclick="deleteReply({{ $reply->id }})"
                                                                            class="text-red-600 hover:text-red-800 text-xs font-medium">
                                                                        Delete
                                                                    </button>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12" id="no-comments-{{ $journal->id }}">
                            <div class="text-6xl mb-4">💭</div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No comments yet</h3>
                            <p class="text-gray-600">Be the first to share your thoughts about this journal entry!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// CSRF token for AJAX requests
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Upvote functionality
function upvoteJournal(journalId) {
    console.log('Upvote function called for journal:', journalId);
    
    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    console.log('CSRF Token:', csrfToken);
    
    fetch(`/mood-journal/${journalId}/upvote`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Response is not JSON!');
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Data received:', data);
        if (data.success) {
            const upvoteCount = document.getElementById(`upvote-count-${journalId}`);
            const upvoteButton = document.querySelector(`[onclick="upvoteJournal(${journalId})"]`);
            
            console.log('Upvote count element:', upvoteCount);
            console.log('Upvote button element:', upvoteButton);
            
            upvoteCount.textContent = `${data.upvotes} likes`;
            
            // Toggle button emoji
            if (data.upvoted) {
                upvoteButton.querySelector('#upvote-emoji-' + journalId).textContent = '❤️';
                upvoteButton.classList.remove('text-gray-500');
                upvoteButton.classList.add('text-red-500');
            } else {
                upvoteButton.querySelector('#upvote-emoji-' + journalId).textContent = '🤍';
                upvoteButton.classList.remove('text-red-500');
                upvoteButton.classList.add('text-gray-500');
            }
        }
    })
    .catch(error => {
        console.error('Error details:', error);
        console.error('Error message:', error.message);
    });
}

// Comment functionality
function addComment(journalId) {
    const form = document.getElementById(`comment-form-${journalId}`);
    const textarea = document.getElementById(`comment-content-${journalId}`);
    const errorDiv = document.getElementById(`comment-error-${journalId}`);
    const submitButton = form.querySelector('button[type="submit"]');
    
    // Prevent double submission
    if (submitButton.disabled) {
        return;
    }
    
    // Clear previous errors
    errorDiv.classList.add('hidden');
    errorDiv.textContent = '';
    
    // Disable form during submission
    submitButton.disabled = true;
    submitButton.textContent = 'Adding...';
    
    fetch(`/mood-journal/${journalId}/comment`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            content: textarea.value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear form
            textarea.value = '';
            
            // Update comment count
            const countSpan = document.getElementById(`comment-count-${journalId}`);
            countSpan.textContent = `${data.commentCount} comments`;
            
            // Add new comment to the list
            const commentsContainer = document.getElementById(`comments-container-${journalId}`);
            const noCommentsDiv = document.getElementById(`no-comments-${journalId}`);
            
            if (noCommentsDiv) {
                noCommentsDiv.remove();
            }
            
            const newCommentHtml = `
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl p-6 border border-gray-100" id="comment-${data.comment.id}">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            @if(Auth::user()->profile_picture)
                                <img src="/{{ Auth::user()->profile_picture }}" 
                                     alt="{{ Auth::user()->name }}'s profile picture"
                                     class="w-12 h-12 rounded-full object-cover border-2 border-yellow-200 shadow-md">
                            @else
                                <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center text-white font-semibold text-lg shadow-md">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-semibold text-gray-900 text-lg">{{ Auth::user()->name }}</span>
                                <span class="text-sm text-gray-500 flex items-center">
                                    <span class="mr-2">🕐</span>
                                    Just now
                                </span>
                            </div>
                            <p class="text-gray-700 text-lg leading-relaxed">${data.comment.content}</p>
                            
                            <!-- Comment Actions -->
                            <div class="mt-4 flex space-x-4">
                                <a href="/mood-journal/comment/${data.comment.id}/edit" 
                                   class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                    <span class="mr-2">✏️</span>
                                    Edit
                                </a>
                                <button type="button" 
                                        onclick="deleteComment(${data.comment.id})"
                                        class="text-red-600 hover:text-red-800 font-medium flex items-center">
                                    <span class="mr-2">🗑️</span>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            commentsContainer.insertAdjacentHTML('afterbegin', newCommentHtml);
            
        } else {
            // Show error
            errorDiv.textContent = data.message || 'Failed to add comment';
            errorDiv.classList.remove('hidden');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorDiv.textContent = 'An error occurred while adding the comment';
        errorDiv.classList.remove('hidden');
    })
    .finally(() => {
        submitButton.disabled = false;
        submitButton.textContent = '💭 Share Comment';
    });
}

// Delete comment functionality
function deleteComment(commentId) {
    if (!confirm('Are you sure you want to delete this comment?')) {
        return;
    }
    
    fetch(`/mood-journal/comment/${commentId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove comment from DOM
            const commentElement = document.getElementById(`comment-${commentId}`);
            commentElement.remove();
            
            // Update comment count
            const journalId = data.journalId;
            const countSpan = document.getElementById(`comment-count-${journalId}`);
            countSpan.textContent = `${data.commentCount} comments`;
            
            // Show no comments message if no comments left
            const commentsContainer = document.getElementById(`comments-container-${journalId}`);
            if (commentsContainer.children.length === 0) {
                commentsContainer.innerHTML = `
                    <div class="text-center py-8" id="no-comments-${journalId}">
                        <p class="text-gray-500">No comments yet. Be the first to comment!</p>
                    </div>
                `;
            }
        } else {
            console.error('Delete failed:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Set up form submission handlers
document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('comment-form-{{ $journal->id }}');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            addComment({{ $journal->id }});
        });
    }
});

// Reply functionality
function toggleReplyForm(commentId) {
    const replyForm = document.getElementById(`reply-form-${commentId}`);
    replyForm.classList.toggle('hidden');
}

function addReply(commentId) {
    const form = event.target;
    const textarea = form.querySelector('textarea[name="content"]');
    const content = textarea.value.trim();
    
    if (!content) {
        return;
    }
    
    // Show loading state
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    submitButton.disabled = true;
    submitButton.textContent = 'Adding...';
    
    fetch(`/mood-journal/comment/${commentId}/reply`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            content: content
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Clear form and hide it
            textarea.value = '';
            toggleReplyForm(commentId);
            
            // Add new reply to the DOM
            console.log('Looking for comment element with ID:', `comment-${commentId}`);
            const commentElement = document.getElementById(`comment-${commentId}`);
            if (!commentElement) {
                console.error('Comment element not found for comment ID:', commentId);
                console.log('Available comment elements:', document.querySelectorAll('[id^="comment-"]'));
                return;
            }
            console.log('Found comment element:', commentElement);
            
            let repliesSection = commentElement.querySelector('.ml-8');
            
            if (!repliesSection) {
                // Create replies section if it doesn't exist
                repliesSection = document.createElement('div');
                repliesSection.className = 'mt-4 ml-8 space-y-3';
                repliesSection.innerHTML = '<div class="text-sm text-gray-500 font-medium mb-2">💬 Replies:</div>';
                commentElement.appendChild(repliesSection);
            }
            
            const newReplyHtml = `
                <div class="bg-white rounded-lg p-4 border border-gray-200" id="reply-${data.reply.id}">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            @if(Auth::user()->profile_picture)
                                <img src="/{{ Auth::user()->profile_picture }}" 
                                     alt="{{ Auth::user()->name }}'s profile picture"
                                     class="w-8 h-8 rounded-full object-cover border border-gray-200">
                            @else
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-900 text-sm">{{ Auth::user()->name }}</span>
                                <span class="text-xs text-gray-500">Just now</span>
                            </div>
                            <p class="text-gray-700 text-sm">${data.reply.content}</p>
                            
                            <!-- Reply Actions -->
                            <div class="mt-2 flex space-x-3">
                                <a href="/mood-journal/reply/${data.reply.id}/edit" 
                                   class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                    Edit
                                </a>
                                <button type="button" 
                                        onclick="deleteReply(${data.reply.id})"
                                        class="text-red-600 hover:text-red-800 text-xs font-medium">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            repliesSection.insertAdjacentHTML('beforeend', newReplyHtml);
            
        } else {
            console.error('Reply failed:', data.message);
            alert('Failed to add reply: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error adding reply:', error);
        alert('Error adding reply. Please try again.');
    })
    .finally(() => {
        // Reset button state
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    });
}

function deleteReply(replyId) {
    if (!confirm('Are you sure you want to delete this reply?')) {
        return;
    }
    
    fetch(`/mood-journal/reply/${replyId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove reply from DOM
            const replyElement = document.getElementById(`reply-${replyId}`);
            replyElement.remove();
            
            // Check if this was the last reply and remove replies section if empty
            const repliesSection = replyElement.parentElement;
            if (repliesSection.children.length === 1) { // Only the header remains
                repliesSection.remove();
            }
        } else {
            console.error('Delete reply failed:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
@endsection 