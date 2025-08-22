@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-yellow-50 to-orange-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-purple-500 to-yellow-500 rounded-full mb-6 shadow-lg">
                    <span class="text-4xl">🎨</span>
                </div>
                <h1 class="text-5xl font-bold bg-gradient-to-r from-purple-600 via-yellow-600 to-orange-600 bg-clip-text text-transparent mb-4">
                    Express Yourself
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Discover amazing creations from our creative community. Share your art, music, poetry, and more! ✨
                </p>
                <div class="mt-8">
                    <a href="{{ route('creative-posts.create') }}" 
                       class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-500 to-yellow-500 hover:from-purple-600 hover:to-yellow-600 text-white text-lg font-semibold rounded-full shadow-lg transform hover:scale-105 transition-all duration-200">
                        <span class="mr-3">✨</span>
                        Share Your Creation
                        <span class="ml-3">✨</span>
                    </a>
                </div>
            </div>

            <!-- Category Filter -->
            <div class="mb-12">
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/20">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 text-center">🎭 Browse by Category</h3>
                    <div class="flex flex-wrap justify-center gap-3">
                        <a href="{{ route('creative-posts.index') }}" 
                           class="px-6 py-3 rounded-full {{ !request('category') ? 'bg-gradient-to-r from-purple-500 to-yellow-500 text-white shadow-lg' : 'bg-white/60 text-gray-700 hover:bg-white/80' }} transition-all duration-200 font-medium">
                            🌟 All Creations
                        </a>
                        @foreach($categories as $category => $info)
                            <a href="{{ route('creative-posts.index', ['category' => $category]) }}" 
                               class="px-6 py-3 rounded-full {{ request('category') == $category ? 'bg-gradient-to-r from-purple-500 to-yellow-500 text-white shadow-lg' : 'bg-white/60 text-gray-700 hover:bg-white/80' }} transition-all duration-200 font-medium">
                                {{ $info['emoji'] ?? '🎨' }} {{ $info['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Posts Grid -->
            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($posts as $post)
                        <div class="group bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden border border-white/20">
                            <!-- Media Preview -->
                            @if($post->hasMedia())
                                <div class="relative overflow-hidden">
                                    @if($post->getMediaType() === 'image')
                                        <img src="{{ $post->getMediaUrl() }}" 
                                             alt="{{ $post->title }}" 
                                             class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-500">
                                    @elseif($post->getMediaType() === 'video')
                                        <div class="w-full h-56 bg-gradient-to-br from-purple-100 to-yellow-100 flex items-center justify-center">
                                            <div class="text-center">
                                                <span class="text-6xl mb-2 block">🎬</span>
                                                <p class="text-sm text-gray-600 font-medium">Video Content</p>
                                            </div>
                                        </div>
                                    @elseif($post->getMediaType() === 'audio')
                                        <div class="w-full h-56 bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                                            <div class="text-center">
                                                <span class="text-6xl mb-2 block">🎵</span>
                                                <p class="text-sm text-gray-600 font-medium">Audio Content</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="w-full h-56 bg-gradient-to-br from-green-100 to-blue-100 flex items-center justify-center">
                                            <div class="text-center">
                                                <span class="text-6xl mb-2 block">📄</span>
                                                <p class="text-sm text-gray-600 font-medium">Document</p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Category Badge -->
                                    <div class="absolute top-4 left-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/90 backdrop-blur-sm text-gray-800 shadow-lg">
                                            {{ $post->getCategoryInfo()['emoji'] ?? '🎨' }} {{ $post->getCategoryInfo()['label'] }}
                                        </span>
                                    </div>
                                    
                                    <!-- Privacy Badge -->
                                    @if(!$post->is_public)
                                        <div class="absolute top-4 right-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-500/90 text-white shadow-lg">
                                                🔒 Private
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="w-full h-56 bg-gradient-to-br from-yellow-100 to-orange-100 flex items-center justify-center">
                                    <div class="text-center">
                                        <span class="text-6xl mb-2 block">✨</span>
                                        <p class="text-sm text-gray-600 font-medium">Creative Expression</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Post Info -->
                            <div class="p-6">
                                <h3 class="font-bold text-gray-900 text-lg mb-3 line-clamp-2 group-hover:text-yellow-600 transition-colors">
                                    <a href="{{ route('creative-posts.show', $post->id) }}" class="block">
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                
                                @if($post->description)
                                    <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">{{ $post->description }}</p>
                                @endif
                                
                                <!-- Author Info -->
                                <div class="flex items-center space-x-3 mb-4">
                                    @if($post->user->profile_picture)
                                        <img src="/{{ $post->user->profile_picture }}" 
                                             alt="{{ $post->user->name }}'s profile picture" 
                                             class="w-8 h-8 rounded-full object-cover border border-yellow-200 shadow-sm">
                                    @else
                                        <div class="w-8 h-8 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center text-white font-semibold text-sm shadow-sm">
                                            {{ substr($post->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $post->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                
                                <!-- Interaction Stats -->
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <div class="flex items-center space-x-4">
                                        <button onclick="toggleLike({{ $post->id }})" 
                                                class="flex items-center space-x-2 {{ $post->likes->where('user_id', Auth::id())->count() > 0 ? 'text-yellow-500' : 'text-gray-500' }} hover:text-yellow-500 transition-colors">
                                            <span class="text-lg" id="like-emoji-{{ $post->id }}">{{ $post->likes->where('user_id', Auth::id())->count() > 0 ? '❤️' : '🤍' }}</span>
                                            <span class="text-sm font-medium" id="like-count-{{ $post->id }}">{{ $post->likes->count() }}</span>
                                        </button>
                                        <div class="flex items-center space-x-2 text-gray-500 hover:text-blue-500 transition-colors">
                                            <span class="text-lg">💬</span>
                                            <span class="text-sm font-medium">{{ $post->comments->count() }}</span>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('creative-posts.show', $post->id) }}" 
                                       class="text-yellow-600 hover:text-yellow-700 font-medium text-sm flex items-center group-hover:underline">
                                        View Details
                                        <span class="ml-1 group-hover:translate-x-1 transition-transform">→</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($posts->hasPages())
                    <div class="mt-12 flex justify-center">
                        <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-4 shadow-lg border border-white/20">
                            {{ $posts->links() }}
                        </div>
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-20">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-r from-purple-100 to-pink-100 rounded-full mb-8">
                        <span class="text-6xl">🎨</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">No creative posts yet</h3>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto">
                        Be the first to share your creativity! Inspire others with your art, music, poetry, or any creative expression.
                    </p>
                    <a href="{{ route('creative-posts.create') }}" 
                       class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-500 to-yellow-500 hover:from-purple-600 hover:to-yellow-600 text-white text-lg font-semibold rounded-full shadow-lg transform hover:scale-105 transition-all duration-200">
                        <span class="mr-3">✨</span>
                        Create Your First Post
                        <span class="ml-3">✨</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

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

<script>
function toggleLike(postId) {
    fetch(`/express-yourself/${postId}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const likeCount = document.getElementById(`like-count-${postId}`);
            const likeEmoji = document.getElementById(`like-emoji-${postId}`);
            const likeButton = document.querySelector(`[onclick="toggleLike(${postId})"]`);
            
            likeCount.textContent = data.likes;
            
            // Toggle button emoji and color
            if (data.liked) {
                likeEmoji.textContent = '❤️';
                likeButton.classList.remove('text-gray-500');
                likeButton.classList.add('text-yellow-500');
            } else {
                likeEmoji.textContent = '🤍';
                likeButton.classList.remove('text-yellow-500');
                likeButton.classList.add('text-gray-500');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
@endsection 