@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center mb-6">
                <a href="{{ route('mood_journal.show', $journal->id) }}" class="text-yellow-600 hover:text-yellow-700 mr-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Edit Journal Entry</h1>
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

            <form action="{{ route('mood_journal.update', $journal->id) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        Your Journal Entry
                    </label>
                    <textarea 
                        id="content" 
                        name="content" 
                        rows="8" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                        placeholder="Share your thoughts, feelings, and experiences..."
                        required
                    >{{ old('content', $journal->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Add hashtags to categorize your entry
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($predefinedHashtags as $hashtag => $info)
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="hashtags[]" 
                                    value="{{ $hashtag }}"
                                    class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500"
                                    {{ in_array($hashtag, old('hashtags', $journal->hashtags_array ?? [])) ? 'checked' : '' }}
                                >
                                <span class="text-sm font-medium {{ $info['color'] }}">{{ $info['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('hashtags')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="mood_rating" class="block text-sm font-medium text-gray-700 mb-2">
                        Mood Rating (Optional)
                    </label>
                    <select 
                        id="mood_rating" 
                        name="mood_rating" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                    >
                        <option value="">Select your mood (1-10)</option>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('mood_rating', $journal->mood_rating) == $i ? 'selected' : '' }}>
                                {{ $i }} - {{ $i <= 3 ? 'Low' : ($i <= 7 ? 'Medium' : 'High') }}
                            </option>
                        @endfor
                    </select>
                    @error('mood_rating')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6 p-4 bg-yellow-50 rounded-lg">
                    <h3 class="font-semibold text-yellow-800 mb-2">Hashtag Guide</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <h4 class="font-medium text-yellow-700 mb-1">Positive Emotions</h4>
                            <p class="text-yellow-600">#gratitude, #happy, #blessed, #joy, #love, #peace</p>
                        </div>
                        <div>
                            <h4 class="font-medium text-yellow-700 mb-1">Challenging Emotions</h4>
                            <p class="text-yellow-600">#sad, #anxious, #stressed, #lonely, #angry, #confused</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex space-x-4">
                        <a href="{{ route('mood_journal.show', $journal->id) }}" 
                           class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium">
                            Cancel
                        </a>
                    </div>
                    <div class="flex space-x-4">
                        <form action="{{ route('mood_journal.destroy', $journal->id) }}" method="POST" class="inline" 
                              onsubmit="return confirm('Are you sure you want to delete this journal entry? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="px-4 py-2 text-red-600 hover:text-red-800 font-medium">
                                Delete Entry
                            </button>
                        </form>
                        <button type="submit" 
                                class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white px-6 py-2 rounded-lg font-medium transition-all transform hover:scale-105 shadow-lg">
                            Update Entry
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add interactive hashtag selection
    const hashtagCheckboxes = document.querySelectorAll('input[name="hashtags[]"]');
    
    hashtagCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.nextElementSibling;
            if (this.checked) {
                label.classList.add('font-bold');
            } else {
                label.classList.remove('font-bold');
            }
        });
    });
});
</script>
@endsection 