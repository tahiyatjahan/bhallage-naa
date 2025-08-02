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
        @if($prompt)
            <div class="mb-8 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-3">
                            <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h2 class="text-xl font-semibold text-gray-900">Daily Prompt</h2>
                            <span class="ml-3 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $prompt->category_color }} text-white">
                                {{ $prompt->category_display_name }}
                            </span>
                        </div>
                        <p class="text-lg text-gray-800 mb-4 leading-relaxed">{{ $prompt->prompt }}</p>
                        <p class="text-sm text-gray-600">{{ $prompt->prompt_date->format('l, F j, Y') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Create Journal Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Write Your Response</h1>

            <form action="{{ route('mood_journal.store') }}" method="POST">
                @csrf
                <input type="hidden" name="daily_prompt_id" value="{{ $prompt->id ?? '' }}">

                <!-- Content -->
                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        Your Response
                    </label>
                    <textarea 
                        name="content" 
                        id="content"
                        rows="8" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-500 @enderror"
                        placeholder="Share your thoughts and feelings about this prompt..."
                        required
                    >{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hashtags -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Add hashtags to categorize your entry
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($predefinedHashtags as $tag => $info)
                            @php
                                $colorClasses = [
                                    'green' => 'border-green-300 bg-green-50 text-green-700',
                                    'pink' => 'border-pink-300 bg-pink-50 text-pink-700',
                                    'yellow' => 'border-yellow-300 bg-yellow-50 text-yellow-700',
                                    'blue' => 'border-blue-300 bg-blue-50 text-blue-700',
                                    'orange' => 'border-orange-300 bg-orange-50 text-orange-700',
                                    'purple' => 'border-purple-300 bg-purple-50 text-purple-700',
                                    'gray' => 'border-gray-300 bg-gray-50 text-gray-700',
                                    'red' => 'border-red-300 bg-red-50 text-red-700',
                                    'teal' => 'border-teal-300 bg-teal-50 text-teal-700'
                                ];
                                $colorClass = $colorClasses[$info['color']] ?? 'border-gray-300 bg-gray-50 text-gray-700';
                            @endphp
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors {{ $colorClass }}">
                                <input type="checkbox" name="hashtags[]" value="{{ $tag }}" class="mr-2" {{ in_array($tag, old('hashtags', [])) ? 'checked' : '' }}>
                                <span class="text-sm font-medium">#{{ $info['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Mood Rating -->
                <div class="mb-6">
                    <label for="mood_rating" class="block text-sm font-medium text-gray-700 mb-2">
                        How are you feeling? (Optional)
                    </label>
                    <select 
                        name="mood_rating" 
                        id="mood_rating"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        <option value="">Select your mood...</option>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('mood_rating') == $i ? 'selected' : '' }}>
                                {{ $i }} - {{ $i <= 3 ? 'Very Low' : ($i <= 5 ? 'Low' : ($i <= 7 ? 'Moderate' : ($i <= 9 ? 'High' : 'Very High'))) }}
                            </option>
                        @endfor
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('mood_journal.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-all transform hover:scale-105 shadow-lg">
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
                    <h4 class="font-medium text-gray-800 mb-2">Positive Emotions</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• <strong>#Gratitude</strong> - Thankful for something</li>
                        <li>• <strong>#Happy</strong> - Feeling joyful</li>
                        <li>• <strong>#Excited</strong> - Looking forward to something</li>
                        <li>• <strong>#Motivated</strong> - Driven and focused</li>
                        <li>• <strong>#Confident</strong> - Self-assured</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-medium text-gray-800 mb-2">Challenging Emotions</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• <strong>#Sad</strong> - Feeling down</li>
                        <li>• <strong>#Anxious</strong> - Worried or nervous</li>
                        <li>• <strong>#Stressed</strong> - Overwhelmed</li>
                        <li>• <strong>#Tired</strong> - Exhausted</li>
                        <li>• <strong>#Lonely</strong> - Feeling isolated</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[name="hashtags[]"]');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.closest('label');
            if (this.checked) {
                label.classList.add('ring-2', 'ring-blue-500');
            } else {
                label.classList.remove('ring-2', 'ring-blue-500');
            }
        });
    });
});
</script>
@endsection 