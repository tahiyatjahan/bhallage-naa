@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-yellow-50 to-orange-50">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Back Button -->
            <div class="mb-8">
                <a href="{{ route('creative-posts.index') }}" class="inline-flex items-center text-purple-600 hover:text-purple-800 font-semibold text-lg transition-colors">
                    <span class="mr-3 text-2xl">←</span>
                    Back to Gallery
                </a>
            </div>

            <!-- Main Post Content -->
            <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl overflow-hidden border border-white/20 mb-8">
                <!-- Post Header -->
                <div class="bg-gradient-to-r from-purple-500 to-yellow-500 p-8 text-white">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4 mb-4">
                                <span class="text-4xl">{{ $post->getCategoryInfo()['emoji'] ?? '🎨' }}</span>
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm">
                                    {{ $post->getCategoryInfo()['label'] }}
                                </span>
                                @if(!$post->is_public)
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-500/80 backdrop-blur-sm">
                                        🔒 Private
                                    </span>
                                @endif
                            </div>
                            <h1 class="text-4xl font-bold mb-4">{{ $post->title }}</h1>
                            <div class="flex items-center space-x-6 text-white/90">
                                <div class="flex items-center space-x-3">
                                    @if($post->user->profile_picture)
                                        <img src="/{{ $post->user->profile_picture }}" 
                                             alt="{{ $post->user->name }}'s profile picture" 
                                             class="w-10 h-10 rounded-full object-cover border-2 border-white/30 shadow-md">
                                    @else
                                        <div class="w-10 h-10 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center text-white font-semibold text-lg shadow-md">
                                            {{ substr($post->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="text-xl">👤</span>
                                    <span>{{ $post->user->name }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xl">📅</span>
                                    <span>{{ $post->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        @if(Auth::id() === $post->user_id || Auth::user()->is_admin)
                            <div class="flex space-x-3">
                                <a href="{{ route('creative-posts.edit', $post->id) }}" 
                                   class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white px-6 py-3 rounded-2xl text-sm font-medium transition-all duration-200">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('creative-posts.destroy', $post->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this post?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500/80 hover:bg-red-600/90 backdrop-blur-sm text-white px-6 py-3 rounded-2xl text-sm font-medium transition-all duration-200">
                                        🗑️ Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Post Content -->
                <div class="p-8">
                    @if($post->description)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                <span class="mr-3">💭</span>
                                About This Creation
                            </h3>
                            <p class="text-gray-700 text-lg leading-relaxed bg-gradient-to-r from-purple-50 to-yellow-50 p-6 rounded-2xl border border-yellow-100">
                                {{ $post->description }}
                            </p>
                        </div>
                    @endif

                    <!-- Media Display -->
                    @if($post->hasMedia())
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <span class="mr-3">🎬</span>
                                Media Content
                            </h3>
                            <div class="bg-gradient-to-r from-purple-50 to-yellow-50 rounded-2xl p-6 border border-yellow-100">
                                @if($post->getMediaType() === 'image')
                                    <img src="{{ $post->getMediaUrl() }}" 
                                         alt="{{ $post->title }}" 
                                         class="w-full max-h-96 object-contain rounded-2xl shadow-lg">
                                @elseif($post->getMediaType() === 'video')
                                    <video controls class="w-full max-h-96 rounded-2xl shadow-lg">
                                        <source src="{{ $post->getMediaUrl() }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @elseif($post->getMediaType() === 'audio')
                                    <div class="text-center">
                                        <div class="text-6xl mb-4">🎵</div>
                                        <audio controls class="w-full max-w-md mx-auto">
                                            <source src="{{ $post->getMediaUrl() }}" type="audio/mpeg">
                                            Your browser does not support the audio tag.
                                        </audio>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <div class="text-6xl mb-4">📄</div>
                                        <a href="{{ $post->getMediaUrl() }}" 
                                           target="_blank" 
                                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-500 to-yellow-500 text-white rounded-2xl font-medium hover:from-purple-600 hover:to-yellow-600 transition-all duration-200">
                                            📎 Download File
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Text Content -->
                    @if($post->content)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <span class="mr-3">📖</span>
                                Content
                            </h3>
                            <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-6 rounded-2xl border border-blue-100">
                                <pre class="whitespace-pre-wrap text-gray-700 text-lg leading-relaxed font-sans">{{ $post->content }}</pre>
                            </div>
                        </div>
                    @endif

                    <!-- External Link -->
                    @if($post->external_link)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <span class="mr-3">🔗</span>
                                External Content
                            </h3>
                            <a href="{{ $post->external_link }}" 
                               target="_blank" 
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-blue-500 text-white rounded-2xl font-medium hover:from-green-600 hover:to-blue-600 transition-all duration-200">
                                <span class="mr-3">🌐</span>
                                View External Content
                                <span class="ml-3">↗</span>
                            </a>
                        </div>
                    @endif

                    <!-- Tags -->
                    @if($post->tags && count($post->getTagsArrayAttribute()) > 0)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <span class="mr-3">🏷️</span>
                                Tags
                            </h3>
                            <div class="flex flex-wrap gap-3">
                                @foreach($post->getTagsArrayAttribute() as $tag)
                                    <span class="bg-gradient-to-r from-purple-100 to-yellow-100 text-purple-800 px-4 py-2 rounded-full text-sm font-medium border border-yellow-200">
                                        #{{ $tag }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Interaction Bar -->
                <div class="px-8 py-6 bg-gradient-to-r from-purple-50 to-yellow-50 border-t border-yellow-100">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-8">
                            <!-- Like Button -->
                            <button onclick="toggleLike({{ $post->id }})" 
                                    class="flex items-center space-x-3 {{ $post->likes->where('user_id', Auth::id())->count() > 0 ? 'text-yellow-600' : 'text-gray-500' }} hover:text-yellow-600 transition-all duration-200 group">
                                <span class="text-3xl group-hover:scale-110 transition-transform">
                                    {{ $post->likes->where('user_id', Auth::id())->count() > 0 ? '❤️' : '🤍' }}
                                </span>
                                <span class="text-lg font-semibold" id="like-count-{{ $post->id }}">{{ $post->likes->count() }} likes</span>
                            </button>

                            <!-- Comment Count -->
                            <div class="flex items-center space-x-3 text-gray-500">
                                <span class="text-3xl">💬</span>
                                <span class="text-lg font-semibold">{{ $post->comments->count() }} comments</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20">
                <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 px-8 py-6 text-white">
                    <h3 class="text-2xl font-bold text-center">💬 Community Discussion</h3>
                </div>

                <!-- Add Comment Form -->
                <div class="p-8 border-b border-gray-100">
                    <form id="comment-form" onsubmit="submitComment(event)">
                        @csrf
                        <div class="space-y-4">
                            <label for="comment-content" class="block text-lg font-semibold text-gray-800">
                                Share your thoughts about this creation:
                            </label>
                            <textarea name="content" id="comment-content" rows="4" required
                                      class="w-full px-6 py-4 border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 text-lg transition-all duration-200 resize-none"
                                      placeholder="What do you think about this creation? Share your thoughts..."></textarea>
                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="bg-gradient-to-r from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-white px-8 py-3 rounded-2xl font-semibold text-lg transition-all duration-200 transform hover:scale-105 shadow-lg">
                                    💭 Share Comment
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Comments List -->
                <div class="p-8">
                    @if($post->comments->count() > 0)
                        <div class="space-y-6">
                            @foreach($post->comments->sortBy('created_at') as $comment)
                                <div class="bg-gradient-to-r from-gray-50 to-blue-50 rounded-2xl p-6 border border-gray-100" id="comment-{{ $comment->id }}">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            @if($comment->user->profile_picture)
                                                <img src="/{{ $comment->user->profile_picture }}" 
                                                     alt="{{ $comment->user->name }}'s profile picture"
                                                     class="w-12 h-12 rounded-full object-cover border-2 border-yellow-200 shadow-md">
                                            @else
                                                <div class="w-12 h-12 bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center text-white font-semibold text-lg shadow-md">
                                                    {{ substr($comment->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-3">
                                                <span class="font-semibold text-gray-900 text-lg">{{ $comment->user->name }}</span>
                                                <span class="text-sm text-gray-500 flex items-center">
                                                    <span class="mr-2">🕐</span>
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            <p class="text-gray-700 text-lg leading-relaxed">{{ $comment->content }}</p>
                                            
                                            <!-- Comment Actions -->
                                            <div class="mt-4 flex items-center justify-between">
                                                <div class="flex space-x-4">
                                                    <button type="button" 
                                                            onclick="toggleReplyForm({{ $comment->id }})"
                                                            class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                                        <span class="mr-2">💬</span>
                                                        Reply
                                                    </button>
                                                    @if(Auth::id() === $comment->user_id)
                                                        <a href="{{ route('creative-posts.edit-comment', $comment->id) }}" 
                                                           class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                                            <span class="mr-2">✏️</span>
                                                            Edit
                                                        </a>
                                                        <button type="button" 
                                                                onclick="deleteComment({{ $comment->id }})"
                                                                class="text-red-600 hover:text-red-800 font-medium flex items-center">
                                                            <span class="mr-2">🗑️</span>
                                                            Delete
                                                        </button>
                                                    @elseif(Auth::user()->is_admin)
                                                        <button type="button" 
                                                                onclick="deleteComment({{ $comment->id }})"
                                                                class="text-red-600 hover:text-red-800 font-medium flex items-center">
                                                            <span class="mr-2">🗑️</span>
                                                            Delete
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Reply Form (Hidden by default) -->
                                            <div id="reply-form-{{ $comment->id }}" class="mt-4 hidden">
                                                <form onsubmit="addReply({{ $comment->id }}); return false;" class="space-y-3">
                                                    @csrf
                                                    <textarea name="content" rows="2" required
                                                              class="w-full px-4 py-2 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 text-sm transition-all duration-200 resize-none"
                                                              placeholder="Write your reply..."></textarea>
                                                    <div class="flex justify-end space-x-2">
                                                        <button type="button" 
                                                                onclick="toggleReplyForm({{ $comment->id }})"
                                                                class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium">
                                                            Cancel
                                                        </button>
                                                        <button type="submit" 
                                                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                                            Reply
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- Replies Section -->
                                            @if($comment->replies->count() > 0)
                                                <div class="mt-4 ml-8 space-y-3">
                                                    <div class="text-sm text-gray-500 font-medium mb-2">💬 Replies:</div>
                                                    @foreach($comment->replies as $reply)
                                                        <div class="bg-white rounded-lg p-4 border border-gray-200" id="reply-{{ $reply->id }}">
                                                            <div class="flex items-start space-x-3">
                                                                <div class="flex-shrink-0">
                                                                    @if($reply->user->profile_picture)
                                                                        <img src="/{{ $reply->user->profile_picture }}" 
                                                                             alt="{{ $reply->user->name }}'s profile picture"
                                                                             class="w-8 h-8 rounded-full object-cover border border-gray-200">
                                                                    @else
                                                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                                                            {{ substr($reply->user->name, 0, 1) }}
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="flex-1">
                                                                    <div class="flex items-center justify-between mb-2">
                                                                        <span class="font-medium text-gray-900 text-sm">{{ $reply->user->name }}</span>
                                                                        <span class="text-xs text-gray-500">
                                                                            {{ $reply->created_at->diffForHumans() }}
                                                                        </span>
                                                                    </div>
                                                                    <p class="text-gray-700 text-sm">{{ $reply->content }}</p>
                                                                    
                                                                    <!-- Reply Actions -->
                                                                    @if($reply->user_id === Auth::id())
                                                                        <div class="mt-2 flex space-x-3">
                                                                            <a href="{{ route('creative-posts.reply.edit', $reply->id) }}" 
                                                                               class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                                                                Edit
                                                                            </a>
                                                                            <button type="button" 
                                                                                    onclick="deleteReply({{ $reply->id }})"
                                                                                    class="text-red-600 hover:text-red-800 text-xs font-medium">
                                                                                Delete
                                                                            </button>
                                                                        </div>
                                                                    @elseif(Auth::user()->is_admin)
                                                                        <div class="mt-2">
                                                                            <button type="button" 
                                                                                    onclick="deleteReply({{ $reply->id }})"
                                                                                    class="text-red-600 hover:text-red-800 text-xs font-medium">
                                                                                Delete
                                                                            </button>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">💭</div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No comments yet</h3>
                            <p class="text-gray-600">Be the first to share your thoughts about this creation!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

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
            const likeButton = document.querySelector(`[onclick="toggleLike(${postId})"]`);
            
            likeCount.textContent = `${data.likes} likes`;
            
            // Toggle button emoji
            if (data.liked) {
                likeButton.querySelector('span').textContent = '❤️';
                likeButton.classList.remove('text-gray-500');
                likeButton.classList.add('text-yellow-600');
            } else {
                likeButton.querySelector('span').textContent = '🤍';
                likeButton.classList.remove('text-yellow-600');
                likeButton.classList.add('text-gray-500');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function submitComment(event) {
    event.preventDefault();
    
    const content = document.getElementById('comment-content').value;
    
    if (!content.trim()) return;
    
    fetch(`/express-yourself/{{ $post->id }}/comment`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ content: content })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear the form
            document.getElementById('comment-content').value = '';
            // Reload the page to show the new comment
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function deleteComment(commentId) {
    if (!confirm('Delete this comment?')) return;
    
    fetch(`/express-yourself/comment/${commentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the page to reflect the deletion
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Reply functionality
function toggleReplyForm(commentId) {
    const replyForm = document.getElementById(`reply-form-${commentId}`);
    replyForm.classList.toggle('hidden');
}

function addReply(commentId) {
    const form = event.target;
    const textarea = form.querySelector('textarea[name="content"]');
    const content = textarea.value.trim();
    
    if (!content) {
        return;
    }
    
    // Show loading state
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    submitButton.disabled = true;
    submitButton.textContent = 'Adding...';
    
    fetch(`/express-yourself/comment/${commentId}/reply`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            content: content
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Clear form and hide it
            textarea.value = '';
            toggleReplyForm(commentId);
            
            // Add new reply to the DOM
            console.log('Looking for comment element with ID:', `comment-${commentId}`);
            const commentElement = document.getElementById(`comment-${commentId}`);
            if (!commentElement) {
                console.error('Comment element not found for comment ID:', commentId);
                console.log('Available comment elements:', document.querySelectorAll('[id^="comment-"]'));
                return;
            }
            console.log('Found comment element:', commentElement);
            
            let repliesSection = commentElement.querySelector('.ml-8');
            
            if (!repliesSection) {
                // Create replies section if it doesn't exist
                repliesSection = document.createElement('div');
                repliesSection.className = 'mt-4 ml-8 space-y-3';
                repliesSection.innerHTML = '<div class="text-sm text-gray-500 font-medium mb-2">💬 Replies:</div>';
                commentElement.appendChild(repliesSection);
            }
            
            const newReplyHtml = `
                <div class="bg-white rounded-lg p-4 border border-gray-200" id="reply-${data.reply.id}">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            @if(Auth::user()->profile_picture)
                                <img src="/{{ Auth::user()->profile_picture }}" 
                                     alt="{{ Auth::user()->name }}'s profile picture"
                                     class="w-8 h-8 rounded-full object-cover border border-gray-200">
                            @else
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-900 text-sm">{{ Auth::user()->name }}</span>
                                <span class="text-xs text-gray-500">Just now</span>
                            </div>
                            <p class="text-gray-700 text-sm">${data.reply.content}</p>
                            
                            <!-- Reply Actions -->
                            <div class="mt-2 flex space-x-3">
                                <a href="/express-yourself/reply/${data.reply.id}/edit" 
                                   class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                    Edit
                                </a>
                                <button type="button" 
                                        onclick="deleteReply(${data.reply.id})"
                                        class="text-red-600 hover:text-red-800 text-xs font-medium">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            repliesSection.insertAdjacentHTML('beforeend', newReplyHtml);
            
        } else {
            console.error('Reply failed:', data.message);
            alert('Failed to add reply: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error adding reply:', error);
        alert('Error adding reply. Please try again.');
    })
    .finally(() => {
        // Reset button state
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    });
}

function deleteReply(replyId) {
    if (!confirm('Are you sure you want to delete this reply?')) {
        return;
    }
    
    fetch(`/express-yourself/reply/${replyId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove reply from DOM
            const replyElement = document.getElementById(`reply-${replyId}`);
            replyElement.remove();
            
            // Check if this was the last reply and remove replies section if empty
            const repliesSection = replyElement.parentElement;
            if (repliesSection.children.length === 1) { // Only the header remains
                repliesSection.remove();
            }
        } else {
            console.error('Delete reply failed:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
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
