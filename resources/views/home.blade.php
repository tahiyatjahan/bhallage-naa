@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-yellow-900 leading-tight">
        Home
    </h2>
@endsection

@section('content')
<style>
    body {
        background: linear-gradient(120deg, #FFFDE7 0%, #FFFBEA 60%, #FFE066 100%);
        animation: gradientBG 8s ease-in-out infinite alternate;
    }
    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        100% { background-position: 100% 50%; }
    }
    .story-card {
        background: linear-gradient(135deg, #FFE066 0%, #FFF9C4 100%);
        border-radius: 20px;
        padding: 1.5rem;
        text-align: center;
        border: 2px solid rgba(255,255,255,0.3);
        box-shadow: 0 4px 20px rgba(255,224,102,0.2);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .story-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(255,224,102,0.3);
    }
    .feature-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .feature-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.15);
    }
    .quick-action-btn {
        background: linear-gradient(135deg, #FFE066 0%, #FFF9C4 100%);
        color: #7C6F1A;
        font-weight: 600;
        padding: 1rem 2rem;
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 15px rgba(255,224,102,0.3);
        transition: all 0.2s;
        text-decoration: none;
        display: inline-block;
    }
    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255,224,102,0.4);
        color: #7C6F1A;
    }
    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        object-fit: cover;
    }
    .activity-item {
        background: white;
        border-radius: 15px;
        padding: 1rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-left: 4px solid #FFE066;
    }
</style>

<div class="max-w-6xl mx-auto py-8 px-4">
    <!-- Welcome Header -->
    <div class="feature-card mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                @if(Auth::user()->profile_picture)
                    <img src="/{{ Auth::user()->profile_picture }}" alt="Profile Picture" class="user-avatar">
                @else
                    <div class="user-avatar bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center text-white text-2xl font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Welcome back, {{ Auth::user()->name }}! 👋</h1>
                    <p class="text-gray-600">Ready to continue your healing journey?</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500">Today</div>
                <div class="text-lg font-semibold text-yellow-800">{{ now()->format('M d') }}</div>
            </div>
        </div>
    </div>

    <!-- Support Notification -->
    @if(isset($unreadSupportReports) && $unreadSupportReports > 0)
        <div class="feature-card mb-8 bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-red-800">You have {{ $unreadSupportReports }} unread support {{ $unreadSupportReports === 1 ? 'message' : 'messages' }}</h3>
                    <p class="text-red-700">We've detected some concerning content in your journal entries and have resources to help.</p>
                </div>
                <div class="flex-shrink-0">
                    <a href="{{ route('support-reports.index') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        View Support
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="story-card">
            <div class="text-4xl mb-2">📝</div>
            <div class="text-2xl font-bold text-yellow-800 mb-1">{{ Auth::user()->moodJournals->count() }}</div>
            <div class="text-sm text-yellow-700">Journal Entries</div>
        </div>
        <div class="story-card">
            <div class="text-4xl mb-2">💭</div>
            <div class="text-2xl font-bold text-yellow-800 mb-1">{{ \App\Models\Whisper::count() }}</div>
            <div class="text-sm text-yellow-700">Secret Whispers</div>
        </div>
        <div class="story-card">
            <div class="text-4xl mb-2">🎨</div>
            <div class="text-2xl font-bold text-yellow-800 mb-1">{{ \App\Models\CreativePost::count() }}</div>
            <div class="text-sm text-yellow-700">Creative Posts</div>
        </div>
        <div class="story-card">
            <div class="text-4xl mb-2">❤️</div>
            <div class="text-2xl font-bold text-yellow-800 mb-1">{{ Auth::user()->moodJournals->sum(function($journal) { return $journal->upvotes->count(); }) }}</div>
            <div class="text-sm text-yellow-700">Total Upvotes</div>
        </div>
    </div>

    <!-- Main Features Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Secret Whispers Card -->
        <div class="feature-card">
            <div class="flex items-center mb-4">
                <div class="text-3xl mr-3">🤫</div>
                <h2 class="text-2xl font-bold text-gray-800">Secret Whispers</h2>
            </div>
            <p class="text-gray-600 mb-6">Share your thoughts anonymously in a safe space. No judgment, just support.</p>
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    <span class="font-semibold">{{ \App\Models\Whisper::count() }}</span> whispers shared
                </div>
                <a href="{{ route('whispers.index') }}" class="quick-action-btn">
                    Explore Whispers
                </a>
            </div>
        </div>

        <!-- Mood Journal Card -->
        <div class="feature-card">
            <div class="flex items-center mb-4">
                <div class="text-3xl mr-3">📖</div>
                <h2 class="text-2xl font-bold text-gray-800">Mood Journal</h2>
            </div>
            <p class="text-gray-600 mb-6">Document your feelings and connect with others on similar journeys.</p>
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    <span class="font-semibold">{{ Auth::user()->moodJournals->count() }}</span> of your entries
                </div>
                <a href="{{ route('mood_journal.index') }}" class="quick-action-btn">
                    View Journal
                </a>
            </div>
        </div>

        <!-- Daily Prompt Card -->
        <div class="feature-card">
            <div class="flex items-center mb-4">
                <div class="text-3xl mr-3">✨</div>
                <h2 class="text-2xl font-bold text-gray-800">Daily Prompt</h2>
            </div>
            <p class="text-gray-600 mb-6">Get inspired with daily prompts to guide your journaling and reflection.</p>
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    <span class="font-semibold">{{ \App\Models\DailyPrompt::count() }}</span> prompts available
                </div>
                <a href="{{ route('daily-prompt.today') }}" class="quick-action-btn">
                    Today's Prompt
                </a>
            </div>
        </div>

        <!-- Express Yourself Card -->
        <div class="feature-card">
            <div class="flex items-center mb-4">
                <div class="text-3xl mr-3">🎨</div>
                <h2 class="text-2xl font-bold text-gray-800">Express Yourself</h2>
            </div>
            <p class="text-gray-600 mb-6">Share your creativity through music, art, poetry, photography, and more.</p>
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    <span class="font-semibold">{{ \App\Models\CreativePost::count() }}</span> creative posts
                </div>
                <a href="{{ route('creative-posts.index') }}" class="quick-action-btn">
                    Explore Creativity
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="feature-card">
        <div class="flex items-center mb-6">
            <div class="text-2xl mr-3">⚡</div>
            <h2 class="text-2xl font-bold text-gray-800">Recent Activity</h2>
        </div>
        
        @php
            $recentJournals = Auth::user()->moodJournals()->latest()->take(3)->get();
            $recentComments = \App\Models\MoodJournalComment::where('user_id', Auth::user()->id)->latest()->take(2)->get();
            $recentCreativePosts = Auth::user()->creativePosts()->latest()->take(2)->get();
        @endphp

        @if($recentJournals->count() > 0 || $recentComments->count() > 0 || $recentCreativePosts->count() > 0)
            <div class="space-y-3">
                @foreach($recentJournals as $journal)
                    <div class="activity-item">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="text-yellow-600">📝</div>
                                <div>
                                    <div class="font-semibold text-gray-800">You wrote a journal entry</div>
                                    <div class="text-sm text-gray-500">{{ $journal->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            <a href="{{ route('mood_journal.show', $journal->id) }}" class="text-yellow-600 hover:text-yellow-700 text-sm font-semibold">View →</a>
                        </div>
                    </div>
                @endforeach

                @foreach($recentCreativePosts as $post)
                    <div class="activity-item">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="text-yellow-600">🎨</div>
                                <div>
                                    <div class="font-semibold text-gray-800">You shared "{{ $post->title }}"</div>
                                    <div class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            <a href="{{ route('creative-posts.show', $post->id) }}" class="text-yellow-600 hover:text-yellow-700 text-sm font-semibold">View →</a>
                        </div>
                    </div>
                @endforeach

                @foreach($recentComments as $comment)
                    <div class="activity-item">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="text-yellow-600">💬</div>
                                <div>
                                    <div class="font-semibold text-gray-800">You commented on a journal</div>
                                    <div class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            <a href="{{ route('mood_journal.show', $comment->mood_journal_id) }}" class="text-yellow-600 hover:text-yellow-700 text-sm font-semibold">View →</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="text-4xl mb-4">🌟</div>
                <h3 class="text-lg font-semibold text-gray-600 mb-2">No activity yet</h3>
                <p class="text-gray-500 mb-4">Start your journey by exploring our features</p>
                <div class="flex justify-center space-x-4 flex-wrap">
                    <a href="{{ route('mood_journal.create') }}" class="quick-action-btn mb-2">Write Journal</a>
                    <a href="{{ route('whispers.create') }}" class="quick-action-btn mb-2">Share Whisper</a>
                    <a href="{{ route('creative-posts.create') }}" class="quick-action-btn mb-2">Express Yourself</a>
                    <a href="{{ route('daily-prompt.today') }}" class="quick-action-btn mb-2">Daily Prompt</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection 