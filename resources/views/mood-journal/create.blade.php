@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Create Mood Journal Entry</h1>
            <p class="text-gray-600">Share your thoughts and feelings with the community</p>
        </div>

        <!-- Daily Prompt Inspiration -->
        @if($todayPrompt)
            <div class="mb-8 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center mb-3">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                    <h2 class="text-lg font-semibold text-gray-900">Today's Writing Prompt</h2>
                    <span class="ml-3 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $todayPrompt->category_color }} text-white">
                        {{ $todayPrompt->category_display_name }}
                    </span>
                </div>
                <p class="text-gray-800 mb-3 leading-relaxed">{{ $todayPrompt->prompt }}</p>
                <p class="text-sm text-gray-600">Use this prompt as inspiration for your journal entry, or write about anything that's on your mind.</p>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('mood_journal.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        How are you feeling today?
                    </label>
                    <textarea 
                        id="content" 
                        name="content" 
                        rows="6" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-500 @enderror"
                        placeholder="Share your thoughts, feelings, or experiences..."
                        required
                    >{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Add hashtags to categorize your entry
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($predefinedHashtags as $tag => $info)
                            @php
                                $colorClasses = [
                                    'green' => 'bg-green-100 text-green-800',
                                    'pink' => 'bg-pink-100 text-pink-800',
                                    'yellow' => 'bg-yellow-100 text-yellow-800',
                                    'blue' => 'bg-blue-100 text-blue-800',
                                    'orange' => 'bg-orange-100 text-orange-800',
                                    'purple' => 'bg-purple-100 text-purple-800',
                                    'gray' => 'bg-gray-100 text-gray-800',
                                    'red' => 'bg-red-100 text-red-800',
                                    'teal' => 'bg-teal-100 text-teal-800'
                                ];
                                $colorClass = $colorClasses[$info['color']] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="hashtags[]" 
                                    value="{{ $tag }}"
                                    class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    {{ in_array($tag, old('hashtags', [])) ? 'checked' : '' }}
                                >
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
                                        #{{ $info['label'] }}
                                    </span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        Select hashtags that best describe your mood or the content of your entry
                    </p>
                </div>

                <div class="mb-6">
                    <label for="mood_rating" class="block text-sm font-medium text-gray-700 mb-2">
                        Mood Rating (Optional)
                    </label>
                    <div class="flex items-center space-x-4">
                        <select 
                            id="mood_rating" 
                            name="mood_rating" 
                            class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">No rating</option>
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ old('mood_rating') == $i ? 'selected' : '' }}>
                                    {{ $i }} - {{ $i <= 3 ? 'Very Low' : ($i <= 5 ? 'Low' : ($i <= 7 ? 'Medium' : ($i <= 9 ? 'High' : 'Very High'))) }}
                                </option>
                            @endfor
                        </select>
                        <span class="text-sm text-gray-500">Rate your mood from 1-10</span>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('mood_journal.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium transition-colors">
                        Post Entry
                    </button>
                </div>
            </form>
        </div>

        <!-- Hashtag Guide -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Hashtag Guide</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Positive Emotions</h4>
                    <div class="space-y-1">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-2">#Gratitude</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800 mr-2">#SelfLove</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mr-2">#Happy</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 mr-2">#Excited</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 mr-2">#Motivated</span>
                    </div>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Challenging Emotions</h4>
                    <div class="space-y-1">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">#Sad</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 mr-2">#Anxious</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mr-2">#Tired</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 mr-2">#Stressed</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 mr-2">#Lonely</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add some interactivity to hashtag selection
    const hashtagCheckboxes = document.querySelectorAll('input[name="hashtags[]"]');
    
    hashtagCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.closest('label');
            if (this.checked) {
                label.classList.add('bg-blue-50', 'border-blue-300');
            } else {
                label.classList.remove('bg-blue-50', 'border-blue-300');
            }
        });
    });
});
</script>
@endsection 