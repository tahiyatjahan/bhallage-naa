@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-yellow-900 leading-tight">
        Mood Journal Entry
    </h2>
@endsection

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <div class="bg-yellow-100 border-l-4 border-yellow-700 p-6 rounded shadow mb-6">
        <div class="font-bold text-yellow-900 mb-1">{{ $journal->user->name }}</div>
        <p class="text-yellow-900 text-lg">{{ $journal->content }}</p>
        <div class="flex items-center mt-2 space-x-4">
            <form method="POST" action="{{ route('mood_journal.upvote', $journal->id) }}">
                @csrf
                <button type="submit" class="flex items-center px-3 py-1 rounded {{ $journal->upvotes->where('user_id', auth()->id())->count() ? 'bg-yellow-700 text-white' : 'bg-white text-yellow-700 border border-yellow-700' }} hover:bg-yellow-700 hover:text-white">
                    <span class="mr-2">&#x1F44D;</span> {{ $journal->upvotes->count() }}
                </button>
            </form>
        </div>
        <div class="text-xs text-yellow-700 mt-2">Posted {{ $journal->created_at->diffForHumans() }}</div>
    </div>
    <div class="bg-white rounded shadow p-4">
        <h4 class="font-semibold text-yellow-900 mb-2">Comments</h4>
        <div class="space-y-2 mb-2">
            @forelse($journal->comments as $comment)
                <div class="bg-yellow-50 rounded px-3 py-2 shadow text-sm flex items-center justify-between">
                    <div>
                        <span class="font-bold text-yellow-900">{{ $comment->user->name }}:</span> {{ $comment->content }}
                        <span class="text-xs text-gray-500 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    @if($comment->user_id === auth()->id())
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="toggleEditForm('edit-comment-{{ $comment->id }}')" class="ml-2 text-blue-600 font-bold">Edit</button>
                            <form method="POST" action="{{ route('mood_journal.comment.delete', $comment->id) }}" onsubmit="return confirm('Delete this comment?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-2 text-red-600 font-bold">Delete</button>
                            </form>
                        </div>
                    @endif
                </div>
                @if($comment->user_id === auth()->id())
                    <form id="edit-comment-{{ $comment->id }}" method="POST" action="{{ route('mood_journal.comment.update', $comment->id) }}" class="hidden mt-2">
                        @csrf
                        @method('PATCH')
                        <div class="flex items-center space-x-2">
                            <input type="text" name="content" value="{{ $comment->content }}" class="flex-1 border border-yellow-700 rounded px-2 py-1" maxlength="1000" required>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-1 rounded">Save</button>
                            <button type="button" onclick="toggleEditForm('edit-comment-{{ $comment->id }}')" class="text-gray-600 font-bold">Cancel</button>
                        </div>
                    </form>
                @endif
            @empty
                <div class="text-yellow-700">No comments yet.</div>
            @endforelse
        </div>
        <form method="POST" action="{{ route('mood_journal.comment', $journal->id) }}">
            @csrf
            <div class="flex items-center space-x-2">
                <input type="text" name="content" class="flex-1 border border-yellow-700 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-yellow-700" placeholder="Add a comment..." maxlength="1000" required>
                <button type="submit" class="bg-yellow-700 hover:bg-yellow-800 text-white font-bold px-4 py-1 rounded">Comment</button>
            </div>
            @error('content')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
        </form>
    </div>
    <div class="mt-4">
        <a href="{{ route('profile.show') }}" class="text-yellow-700 underline">Back to Profile</a>
    </div>
</div>
@endsection

<script>
function toggleEditForm(id) {
    var form = document.getElementById(id);
    if (form) {
        form.classList.toggle('hidden');
    }
}
</script> 