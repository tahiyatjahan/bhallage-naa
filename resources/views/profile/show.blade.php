@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Profile
    </h2>
@endsection

@section('content')
<style>
    .profile-cover {
        background: linear-gradient(135deg, #FFE066 0%, #FFF9C4 50%, #FFE066 100%);
        height: 200px;
        border-radius: 20px 20px 0 0;
        position: relative;
    }
    .profile-pic-container {
        position: absolute;
        bottom: -50px;
        left: 50%;
        transform: translateX(-50%);
    }
    .profile-pic {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        object-fit: cover;
    }
    .profile-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.2);
    }
    .stats-card {
        background: linear-gradient(135deg, #FFFDE7 0%, #FFFBEA 100%);
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        border: 1px solid rgba(255,224,102,0.2);
    }
    .journal-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid rgba(255,224,102,0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .journal-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .edit-btn {
        background: linear-gradient(135deg, #FFE066 0%, #FFF9C4 100%);
        color: #7C6F1A;
        font-weight: 600;
        padding: 0.75rem 2rem;
        border-radius: 25px;
        border: none;
        box-shadow: 0 2px 10px rgba(255,224,102,0.3);
        transition: all 0.2s;
    }
    .edit-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(255,224,102,0.4);
    }
</style>

<div class="max-w-4xl mx-auto py-8 px-4">
    <!-- Profile Header Card -->
    <div class="profile-card mb-8">
        <div class="profile-cover">
            <div class="profile-pic-container">
                @if($user->profile_picture)
                    <img src="/{{ $user->profile_picture }}" alt="Profile Picture" class="profile-pic">
                @else
                    <div class="profile-pic bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-white text-4xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>
        </div>
        
        <div class="pt-16 pb-8 px-8 text-center">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $user->name }}</h1>
            <p class="text-gray-600 mb-6">{{ $user->email }}</p>
            <button class="edit-btn" onclick="window.location.href='{{ route('profile.edit') }}'">
                Edit Profile
            </button>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="stats-card">
            <div class="text-3xl font-bold text-yellow-800 mb-2">{{ $user->moodJournals->count() }}</div>
            <div class="text-sm text-yellow-700">Journal Entries</div>
        </div>
        <div class="stats-card">
            <div class="text-3xl font-bold text-yellow-800 mb-2">{{ $user->moodJournals->sum(function($journal) { return $journal->upvotes->count(); }) }}</div>
            <div class="text-sm text-yellow-700">Total Upvotes</div>
        </div>
        <div class="stats-card">
            <div class="text-3xl font-bold text-yellow-800 mb-2">{{ $user->moodJournals->sum(function($journal) { return $journal->comments->count(); }) }}</div>
            <div class="text-sm text-yellow-700">Total Comments</div>
        </div>
    </div>

    <!-- Journal Entries Section -->
    <div class="profile-card p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <span class="mr-3">📝</span>
            Your Mood Journal Entries
        </h2>
        
        @if($user->moodJournals->count())
            <div class="space-y-4">
                @foreach($user->moodJournals as $journal)
                    <div class="journal-card">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                            <div class="flex-1">
                                <p class="text-gray-800 text-lg mb-2 leading-relaxed">{{ $journal->content }}</p>
                                <div class="flex items-center text-sm text-gray-500 space-x-4">
                                    <span>📅 {{ $journal->created_at->diffForHumans() }}</span>
                                    <span>👍 {{ $journal->upvotes->count() }} upvotes</span>
                                    <span>💬 {{ $journal->comments->count() }} comments</span>
                                </div>
                            </div>
                            <div class="mt-4 md:mt-0 md:ml-6">
                                <a href="{{ route('mood_journal.show', $journal->id) }}" 
                                   class="inline-block bg-gradient-to-r from-yellow-500 to-yellow-600 text-white font-semibold px-6 py-2 rounded-full hover:from-yellow-600 hover:to-yellow-700 transition-all duration-200 transform hover:scale-105">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📝</div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No journal entries yet</h3>
                <p class="text-gray-500 mb-6">Start your healing journey by writing your first mood journal entry</p>
                <a href="{{ route('mood_journal.create') }}" 
                   class="inline-block bg-gradient-to-r from-yellow-500 to-yellow-600 text-white font-semibold px-6 py-3 rounded-full hover:from-yellow-600 hover:to-yellow-700 transition-all duration-200">
                    Write Your First Entry
                </a>
            </div>
        @endif
    </div>
</div>
@endsection 