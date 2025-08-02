@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('support-reports.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Support Reports
            </a>
        </div>

        @if(session('status'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('status') }}
            </div>
        @endif

        <!-- Support Report Header -->
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg shadow-md p-6 mb-8 border-l-4 border-blue-500">
            <div class="flex items-center mb-4">
                <div class="text-blue-600 mr-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Support Resources</h1>
                    <p class="text-gray-600">You're not alone. Help is available.</p>
                </div>
            </div>
            
            <div class="mb-4">
                <h3 class="font-semibold text-gray-900 mb-2">Keywords Detected:</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($supportReport->keywords_detected as $category => $data)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            {{ ucfirst(str_replace('_', ' ', $category)) }}
                        </span>
                    @endforeach
                </div>
            </div>

            <div class="text-sm text-gray-500">
                Generated {{ $supportReport->created_at->diffForHumans() }}
            </div>
        </div>

        <!-- Support Message -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Support Message</h2>
            <div class="prose max-w-none">
                {!! nl2br(e($supportReport->message)) !!}
            </div>
        </div>

        <!-- Support Resources -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Support Resources</h2>
            <div class="space-y-4">
                @foreach($supportReport->support_resources as $name => $url)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">{{ $name }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ $url }}</p>
                            </div>
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                                Visit Resource
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Related Journal Entry -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Related Journal Entry</h2>
            <div class="border-l-4 border-gray-200 pl-4">
                <div class="mb-2">
                    <span class="text-sm text-gray-500">{{ $supportReport->moodJournal->created_at->format('l, F j, Y \a\t g:i A') }}</span>
                </div>
                <p class="text-gray-800 leading-relaxed">{{ $supportReport->moodJournal->content }}</p>
                <div class="mt-4">
                    <a href="{{ route('mood_journal.show', $supportReport->moodJournal->id) }}" 
                       class="text-blue-600 hover:text-blue-800 font-medium">
                        View Full Entry →
                    </a>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between">
            <div class="flex space-x-4">
                @if(!$supportReport->is_read)
                    <form action="{{ route('support-reports.read', $supportReport->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                            Mark as Read
                        </button>
                    </form>
                @else
                    <span class="text-green-600 font-medium">✓ Read {{ $supportReport->read_at->diffForHumans() }}</span>
                @endif
            </div>
            <form action="{{ route('support-reports.destroy', $supportReport->id) }}" method="POST" class="inline"
                  onsubmit="return confirm('Are you sure you want to delete this support report?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                    Delete Report
                </button>
            </form>
        </div>

        <!-- Emergency Notice -->
        <div class="mt-8 bg-red-50 border border-red-200 rounded-lg p-6">
            <div class="flex items-start">
                <div class="text-red-600 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-red-800 mb-2">Emergency Support</h3>
                    <p class="text-red-700 text-sm">
                        If you're in immediate danger or experiencing a crisis, please call <strong>999</strong> (Bangladesh Emergency Services) or your local emergency services immediately.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 