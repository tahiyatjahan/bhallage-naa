@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Express Yourself</h1>
                <p class="text-gray-600">Share your creativity with the community</p>
            </div>
            <a href="{{ route('creative-posts.create') }}" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-3 rounded-lg font-medium transition-all transform hover:scale-105 shadow-lg">
                Share Your Creativity
            </a>
        </div>
    </div>

    @if(session('status'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('status') }}
        </div>
    @endif

    <!-- Category Filter -->
    <div class="mb-8 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Browse by Category</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
            <a href="{{ route('creative-posts.index') }}" class="flex flex-col items-center p-4 rounded-lg border-2 {{ request()->routeIs('creative-posts.index') && !request('category') ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:border-purple-300 hover:bg-purple-50' }} transition-all">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">All</span>
            </a>
            @foreach($categories as $key => $category)
                <a href="{{ route('creative-posts.category', $key) }}" class="flex flex-col items-center p-4 rounded-lg border-2 {{ request('category') === $key ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:border-purple-300 hover:bg-purple-50' }} transition-all">
                    <div class="w-12 h-12 bg-{{ $category['color'] }}-100 rounded-full flex items-center justify-center mb-2">
                        @switch($category['icon'])
                            @case('music-note')
                                <svg class="w-6 h-6 text-{{ $category['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                </svg>
                                @break
                            @case('palette')
                                <svg class="w-6 h-6 text-{{ $category['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                                </svg>
                                @break
                            @case('book-open')
                                <svg class="w-6 h-6 text-{{ $category['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                @break
                            @case('camera')
                                <svg class="w-6 h-6 text-{{ $category['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                @break
                            @case('pencil')
                                <svg class="w-6 h-6 text-{{ $category['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                                @break
                            @case('video-camera')
                                <svg class="w-6 h-6 text-{{ $category['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                @break
                            @case('scissors')
                                <svg class="w-6 h-6 text-{{ $category['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                @break
                            @default
                                <svg class="w-6 h-6 text-{{ $category['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                        @endswitch
                    </div>
                    <span class="text-sm font-medium text-gray-900">{{ $category['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Creative Posts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($posts as $post)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <!-- Media Preview -->
                @if($post->media_file)
                    <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                        @if($post->getMediaType() === 'image')
                            <img src="{{ $post->media_url }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                        @elseif($post->getMediaType() === 'video')
                            <video class="w-full h-48 object-cover" controls>
                                <source src="{{ $post->media_url }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @elseif($post->getMediaType() === 'audio')
                            <div class="w-full h-48 bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-16 h-16 text-purple-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600">Audio File</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @elseif($post->content)
                    <div class="w-full h-48 bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center p-4">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-blue-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-sm text-gray-600">Text Content</p>
                        </div>
                    </div>
                @elseif($post->external_link)
                    <div class="w-full h-48 bg-gradient-to-br from-green-100 to-blue-100 flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-16 h-16 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            <p class="text-sm text-gray-600">External Link</p>
                        </div>
                    </div>
                @endif

                <!-- Content -->
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $post->getCategoryColorClass() }}">
                            {{ $post->getCategoryInfo()['label'] }}
                        </span>
                        @if($post->is_featured)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Featured
                            </span>
                        @endif
                    </div>

                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $post->title }}</h3>
                    
                    @if($post->description)
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $post->description }}</p>
                    @endif

                    <!-- Tags -->
                    @if($post->tags_array && count($post->tags_array) > 0)
                        <div class="mb-3">
                            <div class="flex flex-wrap gap-1">
                                @foreach(array_slice($post->tags_array, 0, 3) as $tag)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        #{{ ucfirst($tag) }}
                                    </span>
                                @endforeach
                                @if(count($post->tags_array) > 3)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        +{{ count($post->tags_array) - 3 }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- User Info -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            @if($post->user->profile_picture)
                                <img src="/{{ $post->user->profile_picture }}" alt="Profile Picture" class="w-6 h-6 rounded-full object-cover">
                            @else
                                <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                                    <span class="text-gray-600 font-semibold text-xs">{{ substr($post->user->name, 0, 1) }}</span>
                                </div>
                            @endif
                            <span class="text-sm text-gray-600">{{ $post->user->name }}</span>
                        </div>
                        <span class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                        <div class="flex items-center space-x-4">
                            <form action="{{ route('creative-posts.like', $post->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="flex items-center space-x-1 text-gray-500 hover:text-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    <span class="text-sm">{{ $post->likes->count() }}</span>
                                </button>
                            </form>
                            <a href="{{ route('creative-posts.show', $post->id) }}" class="flex items-center space-x-1 text-gray-500 hover:text-blue-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <span class="text-sm">{{ $post->comments->count() }}</span>
                            </a>
                        </div>
                        <a href="{{ route('creative-posts.show', $post->id) }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-lg shadow-md p-8 text-center">
                <div class="text-gray-400 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No creative posts yet</h3>
                <p class="text-gray-600 mb-4">Be the first to share your creativity with the community!</p>
                <a href="{{ route('creative-posts.create') }}" class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white px-6 py-3 rounded-lg font-medium transition-all transform hover:scale-105 shadow-lg">
                    Share Your First Creation
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($posts->hasPages())
        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    @endif
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection 