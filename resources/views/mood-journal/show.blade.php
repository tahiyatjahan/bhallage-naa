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

        @if(session('status'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('status') }}
            </div>
        @endif

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
                    <form action="{{ route('mood_journal.upvote', $journal->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center space-x-2 text-gray-500 hover:text-blue-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                            </svg>
                            <span class="font-medium">{{ $journal->upvotes->count() }} upvotes</span>
                        </button>
                    </form>
                    <div class="flex items-center space-x-2 text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <span class="font-medium">{{ $journal->comments->count() }} comments</span>
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
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Comments</h2>

            <!-- Add Comment Form -->
            <form action="{{ route('mood_journal.comment', $journal->id) }}" method="POST" class="mb-8">
                @csrf
                <div class="mb-4">
                    <textarea 
                        name="content" 
                        rows="3" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-500 @enderror"
                        placeholder="Add a comment..."
                        required
                    >{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                    Add Comment
                </button>
            </form>

            <!-- Comments List -->
            <div class="space-y-4">
                @forelse($journal->comments as $comment)
                    <div class="border-b border-gray-200 pb-4 last:border-b-0">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center space-x-3">
                                @if($comment->user->profile_picture)
                                    <img src="/{{ $comment->user->profile_picture }}" alt="Profile Picture" class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                        <span class="text-gray-600 font-semibold text-sm">{{ substr($comment->user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $comment->user->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @if($comment->user_id === Auth::id() || Auth::user()->is_admin)
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('mood_journal.comment.edit', $comment->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">Edit</a>
                                    <form action="{{ route('mood_journal.comment.delete', $comment->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        <p class="text-gray-800 ml-11">{{ $comment->content }}</p>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500">No comments yet. Be the first to comment!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection 