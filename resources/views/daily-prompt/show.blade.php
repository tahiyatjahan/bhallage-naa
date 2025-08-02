@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Daily Prompt</h1>
            <p class="text-gray-600">{{ $todayPrompt->prompt_date->format('l, F j, Y') }}</p>
        </div>

        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Today's Prompt Card -->
        <div class="bg-white rounded-lg shadow-md mb-8">
            <div class="p-8">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $todayPrompt->category_color }}-100 text-{{ $todayPrompt->category_color }}-800">
                            {{ $todayPrompt->category_display_name }}
                        </span>
                        <span class="text-sm text-gray-500">Today's Prompt</span>
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $todayPrompt->prompt_date->format('M j, Y') }}
                    </div>
                </div>
                
                <div class="text-center">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-6 leading-relaxed">
                        "{{ $todayPrompt->prompt }}"
                    </h2>
                    
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('daily-prompt.recent') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            View Recent Prompts
                        </a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('mood_journal.create') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                            Write Mood Journal
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- User's Journal Entry -->
        @if($userJournal)
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
                <div class="flex items-center mb-4">
                    <div class="p-2 rounded-full bg-green-100 text-green-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-green-800 ml-3">You've already written today!</h3>
                </div>
                
                <div class="bg-white rounded-lg p-4 mb-4">
                    <p class="text-gray-800 mb-3">{{ Str::limit($userJournal->content, 200) }}</p>
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <span>Written {{ $userJournal->created_at->diffForHumans() }}</span>
                        <a href="{{ route('mood_journal.show', $userJournal->id) }}" class="text-blue-600 hover:text-blue-800">
                            Read Full Entry
                        </a>
                    </div>
                </div>
                
                <div class="flex space-x-3">
                    <a href="{{ route('mood_journal.show', $userJournal->id) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        View Full Entry
                    </a>
                    <a href="{{ route('mood_journal.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        All Journals
                    </a>
                </div>
            </div>
        @else
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <div class="flex items-center mb-4">
                    <div class="p-2 rounded-full bg-blue-100 text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-blue-800 ml-3">Ready to reflect?</h3>
                </div>
                
                <p class="text-blue-700 mb-4">
                    Take a moment to reflect on today's prompt and write your thoughts in your mood journal.
                </p>
                
                <div class="flex space-x-3">
                    <a href="{{ route('mood_journal.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md font-medium transition-colors">
                        Write Today's Entry
                    </a>
                    <a href="{{ route('mood_journal.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-md font-medium transition-colors">
                        View All Journals
                    </a>
                </div>
            </div>
        @endif

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('mood_journal.index') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Mood Journals</h3>
                        <p class="text-sm text-gray-600">View all your entries</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('daily-prompt.recent') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Prompts</h3>
                        <p class="text-sm text-gray-600">See past prompts</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('home') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Home</h3>
                        <p class="text-sm text-gray-600">Back to dashboard</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection 