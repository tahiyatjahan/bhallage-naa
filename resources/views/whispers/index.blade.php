@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-yellow-900 leading-tight">
        Secret Whispers
    </h2>
@endsection

@section('content')
<style>
    .whisper-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        border: 1px solid rgba(255,255,255,0.2);
        margin-bottom: 1.5rem;
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
        overflow: hidden;
    }
    .whisper-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #FFE066 0%, #FFF9C4 50%, #FFE066 100%);
    }
    .whisper-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.15);
    }
    .anonymous-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FFE066 0%, #FFF9C4 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #FFE066;
        font-size: 1.5rem;
    }
    .report-btn {
        background: transparent;
        border: 1px solid #ff6b6b;
        color: #ff6b6b;
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.2s;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    .report-btn:hover {
        background: #ff6b6b;
        color: white;
        transform: translateY(-1px);
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
        text-decoration: none;
    }
    .fab:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 25px rgba(255,224,102,0.6);
        color: #7C6F1A;
    }
    .stats-badge {
        background: linear-gradient(135deg, #FFFDE7 0%, #FFFBEA 100%);
        border-radius: 15px;
        padding: 1rem;
        text-align: center;
        border: 1px solid rgba(255,224,102,0.2);
        margin-bottom: 2rem;
    }
    .whisper-content {
        background: linear-gradient(135deg, #FFFDE7 0%, #FFFBEA 100%);
        border-radius: 15px;
        padding: 1.5rem;
        margin: 1rem 0;
        border: 1px solid rgba(255,224,102,0.2);
        position: relative;
    }
    .whisper-content::before {
        content: '"';
        position: absolute;
        top: -10px;
        left: 20px;
        font-size: 3rem;
        color: #FFE066;
        font-family: serif;
    }
    .whisper-content::after {
        content: '"';
        position: absolute;
        bottom: -20px;
        right: 20px;
        font-size: 3rem;
        color: #FFE066;
        font-family: serif;
    }
    
    /* Highlighted whisper styles */
    .highlighted-whisper {
        border: 3px solid #FFD700;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 253, 231, 0.98) 100%);
        position: relative;
        overflow: hidden;
    }
    
    .highlighted-whisper::before {
        background: linear-gradient(90deg, #FFD700 0%, #FFA500 50%, #FFD700 100%);
        height: 6px;
    }
    
    .highlighted-whisper::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 60px 60px 0;
        border-color: transparent #FFD700 transparent transparent;
        z-index: 5;
    }
    
    .highlight-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
        z-index: 10;
        transform: rotate(5deg);
        animation: badgeGlow 2s ease-in-out infinite alternate;
    }
    
    @keyframes badgeGlow {
        0% { box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4); }
        100% { box-shadow: 0 6px 20px rgba(255, 215, 0, 0.6), 0 0 30px rgba(255, 215, 0, 0.3); }
    }
    
    .highlight-decoration {
        position: absolute;
        top: 20px;
        left: 20px;
        font-size: 2rem;
        animation: starTwinkle 1.5s ease-in-out infinite;
        z-index: 5;
    }
    
    .crown-decoration {
        position: absolute;
        top: 20px;
        right: 80px;
        font-size: 1.5rem;
        animation: crownGlow 2s ease-in-out infinite alternate;
        z-index: 5;
    }
    
    @keyframes crownGlow {
        0% { filter: drop-shadow(0 0 5px rgba(255, 215, 0, 0.5)); }
        100% { filter: drop-shadow(0 0 15px rgba(255, 215, 0, 0.8)); }
    }
    
    @keyframes starTwinkle {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(1.1); }
    }
    
    .highlight-sparkles {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
        z-index: 1;
    }
    
    .sparkle {
        position: absolute;
        width: 4px;
        height: 4px;
        background: #FFD700;
        border-radius: 50%;
        animation: sparkleFloat 3s linear infinite;
    }
    
    .sparkle:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
    .sparkle:nth-child(2) { top: 60%; left: 80%; animation-delay: 1s; }
    .sparkle:nth-child(3) { top: 80%; left: 20%; animation-delay: 2s; }
    .sparkle:nth-child(4) { top: 30%; left: 70%; animation-delay: 0.5s; }
    .sparkle:nth-child(5) { top: 70%; left: 40%; animation-delay: 1.5s; }
    
    @keyframes sparkleFloat {
        0% { opacity: 0; transform: translateY(0) scale(0); }
        50% { opacity: 1; transform: translateY(-10px) scale(1); }
        100% { opacity: 0; transform: translateY(-20px) scale(0); }
    }
    
    /* Highlight button styles */
    .highlight-btn {
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.2s;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        border: none;
    }
    
    .highlight-btn.highlight {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        color: white;
    }
    
    .highlight-btn.highlight:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
    }
    
    .highlight-btn.unhighlight {
        background: linear-gradient(135deg, #FF6B6B 0%, #FF8E8E 100%);
        color: white;
    }
    
    .highlight-btn.unhighlight:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
    }
    
    /* Featured whispers section styling */
    .featured-whispers-header {
        background: linear-gradient(135deg, #FFFDE7 0%, #FFF8E1 100%);
        border: 2px solid #FFD700;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .featured-whispers-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent 30%, rgba(255, 215, 0, 0.1) 50%, transparent 70%);
        animation: shimmer 3s infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }
</style>

<div class="max-w-4xl mx-auto py-8 px-4">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">🤫 Secret Whispers</h1>
            <p class="text-gray-600">Anonymous thoughts shared in a safe space</p>
        </div>
        <div class="flex items-center space-x-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-yellow-800">{{ $whispers->count() }}</div>
                <div class="text-sm text-gray-600">Total Whispers</div>
            </div>
        </div>
    </div>

    @if(session('status'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('status') }}
        </div>
    @endif

    <!-- Highlighted Whispers Section -->
    @if($whispers->where('is_highlighted', true)->count() > 0)
        <div class="mb-8">
            <div class="featured-whispers-header">
                <div class="text-4xl mb-2">🌟</div>
                <h2 class="text-2xl font-bold text-yellow-700 mb-2">Featured Whispers</h2>
                <p class="text-yellow-600">Special thoughts highlighted by our community moderators</p>
            </div>
            
            <div class="space-y-6">
                @foreach($whispers->where('is_highlighted', true) as $whisper)
                    <div class="whisper-card highlighted-whisper">
                        <!-- Highlighted whisper visual elements -->
                        <div class="highlight-sparkles">
                            <div class="sparkle"></div>
                            <div class="sparkle"></div>
                            <div class="sparkle"></div>
                            <div class="sparkle"></div>
                            <div class="sparkle"></div>
                        </div>
                        <div class="highlight-decoration">✨</div>
                        <div class="crown-decoration">👑</div>
                        <div class="highlight-badge">
                            ⭐ Featured Content
                        </div>
                        
                        <!-- Whisper Header -->
                        <div class="flex items-center mb-4">
                            <div class="anonymous-avatar mr-3">
                                🤫
                            </div>
                            <div class="flex-1">
                                <div class="font-semibold text-gray-800">Anonymous Whisper</div>
                                <div class="text-sm text-gray-500">{{ $whisper->created_at->diffForHumans() }}</div>
                            </div>
                            <div class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">
                                Whisper #{{ $whisper->id }}
                            </div>
                        </div>

                        <!-- Whisper Content -->
                        <div class="whisper-content">
                            <p class="text-gray-800 text-lg leading-relaxed relative z-10">{{ $whisper->content }}</p>
                        </div>

                        <!-- Whisper Actions -->
                        <div class="flex items-center justify-between mt-4">
                            <div class="flex items-center space-x-4">
                                <div class="text-sm text-gray-500 flex items-center">
                                    <span class="mr-2">📅</span> {{ $whisper->created_at->format('M d, Y') }}
                                </div>
                                <div class="text-sm text-gray-500 flex items-center">
                                    <span class="mr-2">🕐</span> {{ $whisper->created_at->format('g:i A') }}
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('whispers.report', $whisper->id) }}" class="report-btn">
                                    ⚠️ Report
                                </a>
                                @if(Auth::user() && Auth::user()->is_admin)
                                    <form action="{{ route('admin.whispers.toggle-highlight', $whisper->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="highlight-btn unhighlight">
                                            ⭐ Unhighlight
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="mb-8">
            <div class="featured-whispers-header">
                <div class="text-4xl mb-2">💫</div>
                <h2 class="text-2xl font-bold text-yellow-700 mb-2">No Featured Whispers Yet</h2>
                <p class="text-yellow-600">Admins can highlight special whispers to feature them here</p>
            </div>
        </div>
    @endif

    <!-- All Whispers Feed -->
    <div class="space-y-6">
        @forelse($whispers->where('is_highlighted', false) as $whisper)
            <div class="whisper-card {{ $whisper->is_highlighted ? 'highlighted-whisper' : '' }}">
                @if($whisper->is_highlighted)
                    <!-- Highlighted whisper visual elements -->
                    <div class="highlight-sparkles">
                        <div class="sparkle"></div>
                        <div class="sparkle"></div>
                        <div class="sparkle"></div>
                        <div class="sparkle"></div>
                        <div class="sparkle"></div>
                    </div>
                    <div class="highlight-decoration">✨</div>
                    <div class="highlight-badge">
                        ⭐ Highlighted by Admin
                    </div>
                @endif
                <!-- Whisper Header -->
                <div class="flex items-center mb-4">
                    <div class="anonymous-avatar mr-3">
                        🤫
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-800">Anonymous Whisper</div>
                        <div class="text-sm text-gray-500">{{ $whisper->created_at->diffForHumans() }}</div>
                    </div>
                    <div class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">
                        Whisper #{{ $whisper->id }}
                    </div>
                </div>

                <!-- Whisper Content -->
                <div class="whisper-content">
                    <p class="text-gray-800 text-lg leading-relaxed relative z-10">{{ $whisper->content }}</p>
                </div>

                <!-- Whisper Actions -->
                <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center space-x-4">
                        <div class="text-sm text-gray-500 flex items-center">
                            <span class="mr-2">📅</span> {{ $whisper->created_at->format('M d, Y') }}
                        </div>
                        <div class="text-sm text-gray-500 flex items-center">
                            <span class="mr-2">🕐</span> {{ $whisper->created_at->format('g:i A') }}
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('whispers.report', $whisper->id) }}" class="report-btn">
                            ⚠️ Report
                        </a>
                        @if(Auth::user() && Auth::user()->is_admin)
                            <form action="{{ route('admin.whispers.toggle-highlight', $whisper->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="highlight-btn {{ $whisper->is_highlighted ? 'unhighlight' : 'highlight' }}">
                                    {{ $whisper->is_highlighted ? '⭐ Unhighlight' : '⭐ Highlight' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="text-6xl mb-4">🤫</div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No whispers yet</h3>
                <p class="text-gray-500 mb-6">Be the first to share an anonymous thought and start the conversation!</p>
                <a href="{{ route('whispers.create') }}" 
                   class="inline-block bg-gradient-to-r from-yellow-500 to-yellow-600 text-white font-semibold px-6 py-3 rounded-full hover:from-yellow-600 hover:to-yellow-700 transition-all duration-200">
                    Share Your First Whisper
                </a>
            </div>
        @endforelse
    </div>
</div>

<!-- Floating Action Button -->
<a href="{{ route('whispers.create') }}" class="fab" title="Share New Whisper">
    🤫
</a>

@endsection 