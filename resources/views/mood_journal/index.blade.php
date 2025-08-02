@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-yellow-900 leading-tight">
        Mood Journal
    </h2>
@endsection

@section('content')
<style>
    .post-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        margin-bottom: 1.5rem;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .post-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.15);
    }
    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 2px solid #FFE066;
        object-fit: cover;
    }
    .interaction-btn {
        background: transparent;
        border: 1px solid #FFE066;
        color: #7C6F1A;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 500;
        transition: all 0.2s;
        cursor: pointer;
    }
    .interaction-btn:hover {
        background: #FFE066;
        color: white;
        transform: translateY(-1px);
    }
    .interaction-btn.active {
        background: #FFE066;
        color: white;
    }
    .comment-section {
        background: #FFFDE7;
        border-radius: 15px;
        padding: 1rem;
        margin-top: 1rem;
    }
    .comment-item {
        background: white;
        border-radius: 10px;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border-left: 3px solid #FFE066;
    }
    .comment-input {
        background: white;
        border: 1px solid #FFE066;
        border-radius: 20px;
        padding: 0.75rem 1rem;
        width: 100%;
        transition: all 0.2s;
    }
    .comment-input:focus {
        outline: none;
        border-color: #FFD54F;
        box-shadow: 0 0 0 3px rgba(255, 224, 102, 0.1);
    }
    .fab {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: linear-gradient(135deg, #FFE066 0%, #FFF9C4 100%);
        color: #7C6F1A;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 4px 20px rgba(255,224,102,0.4);
        transition: all 0.2s;
        z-index: 1000;
        border: none;
        cursor: pointer;
    }
    .fab:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 25px rgba(255,224,102,0.6);
    }
    .stats-badge {
        background: linear-gradient(135deg, #FFFDE7 0%, #FFFBEA 100%);
        border-radius: 15px;
        padding: 1rem;
        text-align: center;
        border: 1px solid rgba(255,224,102,0.2);
        margin-bottom: 2rem;
    }
</style>

<div class="max-w-4xl mx-auto py-8 px-4">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">📖 Mood Journal</h1>
            <p class="text-gray-600">Share your thoughts and connect with others</p>
        </div>
        <div class="flex items-center space-x-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-yellow-800">{{ $journals->count() }}</div>
                <div class="text-sm text-gray-600">Total Posts</div>
            </div>
        </div>
    </div>

    @if(session('status'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('status') }}
        </div>
    @endif

    <!-- Journal Posts Feed -->
    <div class="space-y-6">
        @forelse($journals as $journal)
            <div class="post-card">
                <!-- Post Header -->
                <div class="flex items-center mb-4">
                    @if($journal->user->profile_picture)
                        <img src="/{{ $journal->user->profile_picture }}" alt="Profile Picture" class="user-avatar mr-3">
                    @else
                        <div class="user-avatar mr-3 bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($journal->user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="flex-1">
                        <div class="font-semibold text-gray-800">{{ $journal->user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $journal->created_at->diffForHumans() }}</div>
                    </div>
                    @if($journal->user_id === auth()->id())
                        <div class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">You</div>
                    @endif
                </div>

                <!-- Post Content -->
                <div class="mb-4">
                    <p class="text-gray-800 text-lg leading-relaxed">{{ $journal->content }}</p>
                </div>

                <!-- Post Actions -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <form method="POST" action="{{ route('mood_journal.upvote', $journal->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="interaction-btn {{ $journal->upvotes->where('user_id', auth()->id())->count() ? 'active' : '' }}">
                                <span class="mr-2">👍</span> {{ $journal->upvotes->count() }}
                            </button>
                        </form>
                        <button type="button" onclick="toggleComments('comments-{{ $journal->id }}')" class="interaction-btn">
                            <span class="mr-2">💬</span> {{ $journal->comments->count() }}
                        </button>
                    </div>
                    <div class="text-sm text-gray-500">
                        <span class="mr-2">📅</span> {{ $journal->created_at->format('M d, Y') }}
                    </div>
                </div>

                <!-- Comments Section -->
                <div id="comments-{{ $journal->id }}" class="comment-section hidden">
                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <span class="mr-2">💬</span> Comments
                    </h4>
                    
                    <div class="space-y-2 mb-4">
                        @forelse($journal->comments as $comment)
                            <div class="comment-item">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-1">
                                            <span class="font-semibold text-gray-800 mr-2">{{ $comment->user->name }}</span>
                                            <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-700">{{ $comment->content }}</p>
                                    </div>
                                    @if($comment->user_id === auth()->id())
                                        <div class="flex items-center space-x-2 ml-4">
                                            <button type="button" onclick="toggleEditForm('edit-comment-{{ $comment->id }}')" 
                                                    class="text-blue-600 text-sm hover:text-blue-800 font-medium">
                                                Edit
                                            </button>
                                            <form method="POST" action="{{ route('mood_journal.comment.delete', $comment->id) }}" 
                                                  onsubmit="return confirm('Delete this comment?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 text-sm hover:text-red-800 font-medium">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                
                                @if($comment->user_id === auth()->id())
                                    <form id="edit-comment-{{ $comment->id }}" method="POST" action="{{ route('mood_journal.comment.update', $comment->id) }}" class="hidden mt-3">
                                        @csrf
                                        @method('PATCH')
                                        <div class="flex items-center space-x-2">
                                            <input type="text" name="content" value="{{ $comment->content }}" 
                                                   class="comment-input flex-1" maxlength="1000" required>
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg">
                                                Save
                                            </button>
                                            <button type="button" onclick="toggleEditForm('edit-comment-{{ $comment->id }}')" 
                                                    class="text-gray-600 font-medium">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-4 text-gray-500">
                                <div class="text-2xl mb-2">💭</div>
                                No comments yet. Be the first to comment!
                            </div>
                        @endforelse
                    </div>

                    <!-- Add Comment Form -->
                    <form method="POST" action="{{ route('mood_journal.comment', $journal->id) }}">
                        @csrf
                        <div class="flex items-center space-x-2">
                            <input type="text" name="content" 
                                   class="comment-input flex-1" 
                                   placeholder="Write a comment..." 
                                   maxlength="1000" required>
                            <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium px-6 py-2 rounded-lg transition-colors">
                                Post
                            </button>
                        </div>
                        @error('content')
                            <div class="text-red-500 text-sm mt-2">{{ $message }}</div>
                        @enderror
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📝</div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No journal entries yet</h3>
                <p class="text-gray-500 mb-6">Be the first to share your thoughts and start the conversation!</p>
                <a href="{{ route('mood_journal.create') }}" 
                   class="inline-block bg-gradient-to-r from-yellow-500 to-yellow-600 text-white font-semibold px-6 py-3 rounded-full hover:from-yellow-600 hover:to-yellow-700 transition-all duration-200">
                    Write Your First Entry
                </a>
            </div>
        @endforelse
    </div>
</div>

<!-- Floating Action Button -->
<a href="{{ route('mood_journal.create') }}" class="fab" title="Write New Entry">
    ✏️
</a>

<script>
function toggleComments(id) {
    var section = document.getElementById(id);
    if (section) {
        section.classList.toggle('hidden');
    }
}

function toggleEditForm(id) {
    var form = document.getElementById(id);
    if (form) {
        form.classList.toggle('hidden');
    }
}
</script>
@endsection 