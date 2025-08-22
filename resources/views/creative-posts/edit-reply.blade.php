@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-yellow-50 to-orange-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">✏️ Edit Reply</h1>
            <p class="text-gray-600">Update your reply to the comment</p>
        </div>

        <!-- Post Context -->
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-yellow-500 px-8 py-6 text-white">
                <h3 class="text-xl font-bold">💬 Reply Context</h3>
            </div>
            <div class="p-6 bg-gradient-to-r from-blue-50 to-yellow-50 border-b border-yellow-100">
                <div class="text-sm text-gray-600 mb-2">
                    <strong>Original Comment:</strong> {{ $reply->comment->content }}
                </div>
                <div class="text-sm text-gray-500">
                    <strong>Creative Post:</strong> {{ $reply->comment->creativePost->title }}
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-blue-500 px-8 py-6 text-white">
                <h2 class="text-2xl font-bold text-center">✏️ Update Your Reply</h2>
            </div>
            
            <form action="{{ route('creative-posts.reply.update', $reply->id) }}" method="POST" class="p-8">
                @csrf
                @method('PATCH')
                
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <ul class="text-red-600 text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-6">
                    <div>
                        <label for="content" class="block text-lg font-semibold text-gray-800 mb-3">
                            💭 Your Reply:
                        </label>
                        <textarea name="content" id="content" rows="4" required
                                  class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 text-lg transition-all duration-200 resize-none"
                                  placeholder="Write your reply...">{{ old('content', $reply->content) }}</textarea>
                    </div>

                    <div class="flex space-x-4">
                        <a href="{{ route('creative-posts.show', $reply->comment->creative_post_id) }}" 
                           class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-8 py-4 rounded-2xl font-semibold text-lg transition-all duration-200 transform hover:scale-105 shadow-lg text-center">
                            <span class="mr-2">↩️</span>
                            Cancel
                        </a>
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-green-500 to-blue-500 hover:from-green-600 hover:to-blue-600 text-white px-8 py-4 rounded-2xl font-semibold text-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                            <span class="mr-2">💾</span>
                            Update Reply
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
