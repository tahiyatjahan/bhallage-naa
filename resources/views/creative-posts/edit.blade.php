@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-yellow-50 to-orange-50 py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-purple-500 to-yellow-500 rounded-full mb-6 shadow-lg">
                    <span class="text-4xl">✏️</span>
                </div>
                <h1 class="text-5xl font-bold bg-gradient-to-r from-purple-600 via-yellow-600 to-orange-600 bg-clip-text text-transparent mb-4">
                    Edit Your Creation
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Refine and perfect your creative expression! 🎨✨
                </p>
            </div>

            <!-- Main Form -->
            <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-yellow-500 px-8 py-6">
                    <h2 class="text-2xl font-bold text-white text-center">🎭 Update Your Post</h2>
                </div>

                <form action="{{ route('creative-posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                    @csrf
                    @method('PATCH')
                    
                    <!-- Title -->
                    <div class="space-y-3">
                        <label for="title" class="flex items-center text-lg font-semibold text-gray-800">
                            <span class="mr-3">📝</span>
                            Title *
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required
                               class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 text-lg transition-all duration-200"
                               placeholder="Give your creation a beautiful title...">
                        @error('title')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <span class="mr-2">⚠️</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="space-y-3">
                        <label for="category" class="flex items-center text-lg font-semibold text-gray-800">
                            <span class="mr-3">🏷️</span>
                            Category *
                        </label>
                        <select name="category" id="category" required
                                class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 text-lg transition-all duration-200">
                            <option value="">Choose your creative category...</option>
                            @foreach($categories as $category => $info)
                                <option value="{{ $category }}" {{ old('category', $post->category) == $category ? 'selected' : '' }}>
                                    {{ $info['emoji'] }} {{ $info['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <span class="mr-2">⚠️</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="space-y-3">
                        <label for="description" class="flex items-center text-lg font-semibold text-gray-800">
                            <span class="mr-3">💭</span>
                            Description
                        </label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 text-lg transition-all duration-200 resize-none"
                                  placeholder="Tell us the story behind your creation...">{{ old('description', $post->description) }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <span class="mr-2">⚠️</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Media File -->
                    <div class="space-y-3">
                        <label for="media_file" class="flex items-center text-lg font-semibold text-gray-800">
                            <span class="mr-3">📁</span>
                            Media File
                        </label>
                        @if($post->media_file)
                            <div class="mb-4 p-4 bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl border border-green-200">
                                <div class="flex items-center space-x-3">
                                    <span class="text-2xl">📎</span>
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Current file:</p>
                                        <p class="font-medium text-gray-800">{{ $post->media_file }}</p>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ $post->getMediaUrl() }}" target="_blank" 
                                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-blue-500 text-white rounded-xl text-sm font-medium hover:from-green-600 hover:to-blue-600 transition-all duration-200">
                                        <span class="mr-2">👁️</span>
                                        View Current File
                                    </a>
                                </div>
                            </div>
                        @endif
                        <div class="border-2 border-dashed border-purple-300 rounded-2xl p-8 text-center hover:border-purple-500 transition-colors duration-200">
                            <input type="file" name="media_file" id="media_file"
                                   class="hidden"
                                   onchange="updateFileName(this)">
                            <label for="media_file" class="cursor-pointer">
                                <div class="text-6xl mb-4">📎</div>
                                <p class="text-lg text-gray-600 mb-2">Click to upload a new file</p>
                                <p class="text-sm text-gray-500">Leave empty to keep current file</p>
                                <div id="file-name" class="mt-4 text-purple-600 font-medium hidden"></div>
                            </label>
                        </div>
                        @error('media_file')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <span class="mr-2">⚠️</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Content (for text-based creations like poetry) -->
                    <div class="space-y-3">
                        <label for="content" class="flex items-center text-lg font-semibold text-gray-800">
                            <span class="mr-3">📖</span>
                            Content
                        </label>
                        <textarea name="content" id="content" rows="8"
                                  class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 text-lg transition-all duration-200 resize-none"
                                  placeholder="Share your poetry, story, or any text content here...">{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <span class="mr-2">⚠️</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- External Link -->
                    <div class="space-y-3">
                        <label for="external_link" class="flex items-center text-lg font-semibold text-gray-800">
                            <span class="mr-3">🔗</span>
                            External Link
                        </label>
                        <input type="url" name="external_link" id="external_link" value="{{ old('external_link', $post->external_link) }}"
                               class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 text-lg transition-all duration-200"
                               placeholder="https://example.com (if hosted elsewhere)">
                        <p class="text-sm text-gray-500 flex items-center">
                            <span class="mr-2">💡</span>
                            If your creation is hosted elsewhere (YouTube, SoundCloud, etc.)
                        </p>
                        @error('external_link')
                            <p class="text-red-500 text-sm mt-2 flex items-center">
                                <span class="mr-2">⚠️</span>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Privacy Settings -->
                    <div class="bg-gradient-to-r from-purple-50 to-yellow-50 rounded-2xl p-6 border border-yellow-200">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" name="is_public" id="is_public" value="1" {{ old('is_public', $post->is_public) ? 'checked' : '' }}
                                   class="h-5 w-5 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                            <label for="is_public" class="text-lg font-medium text-gray-800 flex items-center">
                                <span class="mr-2">🌍</span>
                                Make this post public for everyone to see
                            </label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
                        <a href="{{ route('creative-posts.show', $post->id) }}" 
                           class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-8 py-4 rounded-2xl font-semibold text-lg transition-all duration-200 text-center transform hover:scale-105">
                            <span class="mr-2">↩️</span>
                            Back to Post
                        </a>
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-purple-500 to-yellow-500 hover:from-purple-600 hover:to-yellow-600 text-white px-8 py-4 rounded-2xl font-semibold text-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                            <span class="mr-2">💾</span>
                            Update Creation
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tips Section -->
            <div class="mt-12 text-center">
                <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-8 shadow-lg border border-white/20">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">💡 Editing Tips</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div class="text-center">
                            <span class="text-4xl mb-3 block">🎯</span>
                            <h4 class="font-semibold text-gray-800 mb-2">Be Specific</h4>
                            <p class="text-gray-600 text-sm">Clear titles and descriptions help others discover your work</p>
                        </div>
                        <div class="text-center">
                            <span class="text-4xl mb-3 block">🔒</span>
                            <h4 class="font-semibold text-gray-800 mb-2">Privacy Control</h4>
                            <p class="text-gray-600 text-sm">Choose whether to share publicly or keep it private</p>
                        </div>
                        <div class="text-center">
                            <span class="text-4xl mb-3 block">📁</span>
                            <h4 class="font-semibold text-gray-800 mb-2">File Management</h4>
                            <p class="text-gray-600 text-sm">Update media files or keep your current ones</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateFileName(input) {
    const fileName = input.files[0]?.name;
    const fileNameDiv = document.getElementById('file-name');
    
    if (fileName) {
        fileNameDiv.textContent = `📎 ${fileName}`;
        fileNameDiv.classList.remove('hidden');
    } else {
        fileNameDiv.classList.add('hidden');
    }
}
</script>

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
