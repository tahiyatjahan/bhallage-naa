@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Recent Daily Prompts</h1>
                    <p class="text-gray-600">Browse through recent prompts and reflect on past days</p>
                </div>
                <a href="{{ route('daily-prompt.today') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                    Today's Prompt
                </a>
            </div>
        </div>

        <!-- Prompts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($recentPrompts as $prompt)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $prompt->category_color }}-100 text-{{ $prompt->category_color }}-800">
                                    {{ $prompt->category_display_name }}
                                </span>
                                @if($prompt->prompt_date->isToday())
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Today
                                    </span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $prompt->prompt_date->format('M j, Y') }}
                            </div>
                        </div>
                        
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 leading-relaxed">
                            "{{ $prompt->prompt }}"
                        </h3>
                        
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">
                                {{ $prompt->prompt_date->format('l, F j') }}
                            </div>
                            <div class="flex space-x-2">
                                <a href="{{ route('daily-prompt.date', $prompt->prompt_date->format('Y-m-d')) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    View
                                </a>
                                @if($prompt->prompt_date->isToday())
                                    <a href="{{ route('mood_journal.create') }}" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                        Write
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-gray-50 rounded-lg p-8 text-center">
                        <div class="text-gray-400 mb-4">
                            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No prompts yet</h3>
                        <p class="text-gray-600 mb-4">Daily prompts will appear here as they're generated.</p>
                        <a href="{{ route('daily-prompt.today') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                            Check Today's Prompt
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('daily-prompt.today') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Today's Prompt</h3>
                        <p class="text-sm text-gray-600">See today's reflection question</p>
                    </div>
                </div>
            </a>

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

            <a href="{{ route('home') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
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