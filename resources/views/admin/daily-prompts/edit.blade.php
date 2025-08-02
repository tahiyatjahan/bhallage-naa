@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Daily Prompt</h1>
            <p class="text-gray-600">Update the daily prompt for {{ $prompt->prompt_date->format('l, F j, Y') }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.daily-prompts.update', $prompt->id) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-6">
                    <label for="prompt" class="block text-sm font-medium text-gray-700 mb-2">
                        Prompt Question
                    </label>
                    <textarea 
                        id="prompt" 
                        name="prompt" 
                        rows="4" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('prompt') border-red-500 @enderror"
                        placeholder="Enter a reflection question for users..."
                        required
                    >{{ old('prompt', $prompt->prompt) }}</textarea>
                    @error('prompt')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                        Category
                    </label>
                    <select 
                        id="category" 
                        name="category" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-500 @enderror"
                        required
                    >
                        <option value="">Select a category</option>
                        <option value="general" {{ (old('category', $prompt->category) == 'general') ? 'selected' : '' }}>General</option>
                        <option value="gratitude" {{ (old('category', $prompt->category) == 'gratitude') ? 'selected' : '' }}>Gratitude</option>
                        <option value="reflection" {{ (old('category', $prompt->category) == 'reflection') ? 'selected' : '' }}>Reflection</option>
                        <option value="goals" {{ (old('category', $prompt->category) == 'goals') ? 'selected' : '' }}>Goals</option>
                        <option value="self-care" {{ (old('category', $prompt->category) == 'self-care') ? 'selected' : '' }}>Self-Care</option>
                        <option value="relationships" {{ (old('category', $prompt->category) == 'relationships') ? 'selected' : '' }}>Relationships</option>
                    </select>
                    @error('category')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="prompt_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Date
                    </label>
                    <input 
                        type="date" 
                        id="prompt_date" 
                        name="prompt_date" 
                        value="{{ old('prompt_date', $prompt->prompt_date->format('Y-m-d')) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('prompt_date') border-red-500 @enderror"
                        required
                    >
                    @error('prompt_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.daily-prompts.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium transition-colors">
                        Update Prompt
                    </button>
                </div>
            </form>
        </div>

        <!-- Current Prompt Preview -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Prompt</h3>
            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <div class="flex items-center mb-3">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $prompt->category_color }}-100 text-{{ $prompt->category_color }}-800">
                        {{ $prompt->category_display_name }}
                    </span>
                    <span class="ml-2 text-sm text-gray-500">{{ $prompt->prompt_date->format('M j, Y') }}</span>
                </div>
                <p class="text-gray-900 font-medium">"{{ $prompt->prompt }}"</p>
                <div class="mt-3 text-sm text-gray-500">
                    Status: 
                    @if($prompt->is_active)
                        <span class="text-green-600 font-medium">Active</span>
                    @else
                        <span class="text-red-600 font-medium">Inactive</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 