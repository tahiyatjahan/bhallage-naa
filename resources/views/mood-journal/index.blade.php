@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Mood Journals</h1>
                <p class="text-gray-600">Share and explore community thoughts and feelings</p>
            </div>
            <a href="{{ route('mood_journal.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                Create New Entry
            </a>
        </div>
    </div>

    @if(session('status'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('status') }}
        </div>
    @endif

    <!-- Daily Prompt Section -->
    @if($todayPrompt)
        <div class="mb-8 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-3">
                        <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h2 class="text-xl font-semibold text-gray-900">Today's Daily Prompt</h2>
                        <span class="ml-3 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $todayPrompt->category_color }} text-white">
                            {{ $todayPrompt->category_display_name }}
                        </span>
                    </div>
                    <p class="text-lg text-gray-800 mb-4 leading-relaxed">{{ $todayPrompt->prompt }}</p>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('mood_journal.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                            Write About This
                        </a>
                        <a href="{{ route('daily-prompt.today') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            View All Daily Prompts →
                        </a>
                    </div>
                </div>
                <div class="ml-6 text-right">
                    <p class="text-sm text-gray-500">{{ $todayPrompt->prompt_date->format('l, F j, Y') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Hashtag Filter -->
    <div class="mb-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Filter by Hashtag</h2>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('mood_journal.index') }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ request()->has('hashtag') ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800' }} hover:bg-blue-200 transition-colors">
                All Entries
            </a>
            @foreach($predefinedHashtags as $tag => $info)
                @php
                    $colorClasses = [
                        'green' => 'bg-green-100 text-green-800 hover:bg-green-200',
                        'pink' => 'bg-pink-100 text-pink-800 hover:bg-pink-200',
                        'yellow' => 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200',
                        'blue' => 'bg-blue-100 text-blue-800 hover:bg-blue-200',
                        'orange' => 'bg-orange-100 text-orange-800 hover:bg-orange-200',
                        'purple' => 'bg-purple-100 text-purple-800 hover:bg-purple-200',
                        'gray' => 'bg-gray-100 text-gray-800 hover:bg-gray-200',
                        'red' => 'bg-red-100 text-red-800 hover:bg-red-200',
                        'teal' => 'bg-teal-100 text-teal-800 hover:bg-teal-200'
                    ];
                    $colorClass = $colorClasses[$info['color']] ?? 'bg-gray-100 text-gray-800 hover:bg-gray-200';
                    $isActive = request('hashtag') === $tag;
                @endphp
                <a href="{{ route('mood_journal.hashtag', $tag) }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $isActive ? 'ring-2 ring-blue-500 ' . $colorClass : $colorClass }} transition-colors">
                    #{{ $info['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Journal Entries -->
    <div class="space-y-6">
        @forelse($journals as $journal)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        @if($journal->user->profile_picture)
                            <img src="/{{ $journal->user->profile_picture }}" alt="Profile Picture" class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="text-gray-600 font-semibold">{{ substr($journal->user->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $journal->user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $journal->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @if($journal->mood_rating)
                        <div class="text-right">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Mood: {{ $journal->mood_rating }}/10
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Daily Prompt Info -->
                @if($journal->dailyPrompt)
                    <div class="mb-4 p-3 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                        <div class="flex items-center space-x-2 mb-2">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-blue-800">Responding to Daily Prompt</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $journal->dailyPrompt->category_color }} text-white">
                                {{ $journal->dailyPrompt->category_display_name }}
                            </span>
                        </div>
                        <p class="text-sm text-blue-700 italic">"{{ $journal->dailyPrompt->prompt }}"</p>
                        <p class="text-xs text-blue-600 mt-1">{{ $journal->dailyPrompt->prompt_date->format('l, F j, Y') }}</p>
                    </div>
                @endif

                <div class="mb-4">
                    <p class="text-gray-800 leading-relaxed">{{ $journal->content }}</p>
                </div>

                <!-- Hashtags -->
                @if($journal->hashtags_array && count($journal->hashtags_array) > 0)
                    <div class="mb-4">
                        <div class="flex flex-wrap gap-2">
                            @foreach($journal->hashtags_array as $hashtag)
                                <a href="{{ route('mood_journal.hashtag', $hashtag) }}" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $journal->getHashtagColorClass($hashtag) }} hover:opacity-80 transition-opacity">
                                    #{{ ucfirst($hashtag) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <form action="{{ route('mood_journal.upvote', $journal->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center space-x-1 text-gray-500 hover:text-blue-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                </svg>
                                <span>{{ $journal->upvotes->count() }}</span>
                            </button>
                        </form>
                        <a href="{{ route('mood_journal.show', $journal->id) }}" class="flex items-center space-x-1 text-gray-500 hover:text-blue-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span>{{ $journal->comments->count() }} comments</span>
                        </a>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if(Auth::id() === $journal->user_id)
                            <a href="{{ route('mood_journal.edit', $journal->id) }}" class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                                Edit
                            </a>
                            <form action="{{ route('mood_journal.destroy', $journal->id) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('Are you sure you want to delete this journal entry? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Delete
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('mood_journal.show', $journal->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Read More
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <div class="text-gray-400 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No journal entries yet</h3>
                <p class="text-gray-600 mb-4">Be the first to share your thoughts and feelings!</p>
                <a href="{{ route('mood_journal.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                    Create Your First Entry
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($journals->hasPages())
        <div class="mt-8">
            {{ $journals->links() }}
        </div>
    @endif
</div>
@endsection 