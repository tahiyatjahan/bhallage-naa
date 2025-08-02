@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Create Daily Prompt</h1>
            <p class="text-gray-600">Add a new daily prompt for user reflection</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.daily-prompts.store') }}" method="POST">
                @csrf
                
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
                    >{{ old('prompt') }}</textarea>
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
                        <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General</option>
                        <option value="gratitude" {{ old('category') == 'gratitude' ? 'selected' : '' }}>Gratitude</option>
                        <option value="reflection" {{ old('category') == 'reflection' ? 'selected' : '' }}>Reflection</option>
                        <option value="goals" {{ old('category') == 'goals' ? 'selected' : '' }}>Goals</option>
                        <option value="self-care" {{ old('category') == 'self-care' ? 'selected' : '' }}>Self-Care</option>
                        <option value="relationships" {{ old('category') == 'relationships' ? 'selected' : '' }}>Relationships</option>
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
                        value="{{ old('prompt_date', date('Y-m-d')) }}"
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
                        Create Prompt
                    </button>
                </div>
            </form>
        </div>

        <!-- Category Examples -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Category Examples</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">General</h4>
                    <p class="text-sm text-gray-600">How are you feeling right now, and why?</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Gratitude</h4>
                    <p class="text-sm text-gray-600">What made you smile today?</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Reflection</h4>
                    <p class="text-sm text-gray-600">What's something you learned about yourself today?</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Goals</h4>
                    <p class="text-sm text-gray-600">What are you looking forward to tomorrow?</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Self-Care</h4>
                    <p class="text-sm text-gray-600">How did you take care of yourself today?</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Relationships</h4>
                    <p class="text-sm text-gray-600">How did you connect with others today?</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 