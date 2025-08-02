@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-6">
                <a href="{{ route('mood_journal.show', $comment->mood_journal_id) }}" class="text-yellow-600 hover:text-yellow-700 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Edit Comment</h1>
            </div>

            @if(session('status'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('status') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h3 class="font-semibold text-gray-900 mb-2">Original Journal Entry:</h3>
                <p class="text-gray-600">{{ Str::limit($comment->moodJournal->content, 200) }}</p>
            </div>

            <form action="{{ route('mood_journal.comment.update', $comment->id) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        Your Comment
                    </label>
                    <textarea 
                        id="content" 
                        name="content" 
                        rows="4" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                        placeholder="Share your thoughts..."
                        required
                    >{{ old('content', $comment->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('mood_journal.show', $comment->mood_journal_id) }}" 
                       class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white px-6 py-2 rounded-lg font-medium transition-all transform hover:scale-105 shadow-lg">
                        Update Comment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 