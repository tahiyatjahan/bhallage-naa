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

    <!-- Whispers Feed -->
    <div class="space-y-6">
        @forelse($whispers as $whisper)
            <div class="whisper-card">
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
                    <a href="{{ route('whispers.report', $whisper->id) }}" class="report-btn">
                        ⚠️ Report
                    </a>
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