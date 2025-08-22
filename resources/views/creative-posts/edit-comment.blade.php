@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-yellow-50 to-orange-50 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-purple-500 to-yellow-500 rounded-full mb-6 shadow-lg">
                    <span class="text-4xl">✏️</span>
                </div>
                <h1 class="text-5xl font-bold bg-gradient-to-r from-purple-600 via-yellow-600 to-orange-600 bg-clip-text text-transparent mb-4">
                    Edit Comment
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Refine your thoughts about this creation! 💭✨
                </p>
            </div>

            <!-- Main Form -->
            <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-yellow-500 px-8 py-6">
                    <h2 class="text-2xl font-bold text-white text-center">💬 Update Your Comment</h2>
                </div>

                <!-- Post Context -->
                <div class="p-6 bg-gradient-to-r from-purple-50 to-yellow-50 border-b border-yellow-100">
                    <div class="flex items-center space-x-3">
                        <span class="text-2xl">📝</span>
                        <div>
                            <p class="text-sm text-gray-600">Commenting on:</p>
                            <p class="font-semibold text-gray-800">{{ $comment->creativePost->title }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('creative-posts.comment.update', $comment->id) }}" method="POST" class="p-8">
                    @csrf
                    @method('PATCH')
                    
                    <!-- Comment Content -->
                    <div class="space-y-4">
                        <label for="content" class="block text-lg font-semibold text-gray-800 flex items-center">
                            <span class="mr-3">💭</span>
                            Your Comment
                        </label>
                        <textarea name="content" id="content" rows="6" required
                                  class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 text-lg transition-all duration-200 resize-none"
                                  placeholder="Share your refined thoughts about this creation...">{{ old('content', $comment->content) }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <span class="mr-2">⚠️</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 mt-8">
                        <a href="{{ route('creative-posts.show', $comment->creative_post_id) }}" 
                           class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-8 py-4 rounded-2xl font-semibold text-lg transition-all duration-200 text-center transform hover:scale-105">
                            <span class="mr-2">↩️</span>
                            Back to Post
                        </a>
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-blue-500 to-yellow-500 hover:from-blue-600 hover:to-yellow-600 text-white px-8 py-4 rounded-2xl font-semibold text-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                            <span class="mr-2">💾</span>
                            Update Comment
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tips Section -->
            <div class="mt-12 text-center">
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-8 shadow-lg border border-white/20">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">💡 Comment Tips</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div class="text-center">
                            <span class="text-4xl mb-3 block">🤝</span>
                            <h4 class="font-semibold text-gray-800 mb-2">Be Constructive</h4>
                            <p class="text-gray-600 text-sm">Share thoughtful feedback that helps the creator grow</p>
                        </div>
                        <div class="text-center">
                            <span class="text-4xl mb-3 block">💖</span>
                            <h4 class="font-semibold text-gray-800 mb-2">Stay Positive</h4>
                            <p class="text-gray-600 text-sm">Encourage and inspire with your words</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #8b5cf6, #eab308);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #7c3aed, #ca8a04);
}
</style>
@endsection
