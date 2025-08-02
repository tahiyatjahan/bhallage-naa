@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-yellow-900 leading-tight">
        New Mood Journal Entry
    </h2>
@endsection

@section('content')
<div class="max-w-xl mx-auto py-8">
    <div class="bg-yellow-100 p-6 rounded shadow">
        <form method="POST" action="{{ route('mood_journal.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-yellow-900 font-bold mb-2">Your Entry</label>
                <textarea name="content" rows="4" class="w-full border border-yellow-700 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-700" maxlength="2000" required>{{ old('content') }}</textarea>
                @error('content')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="flex justify-between items-center">
                <a href="{{ route('mood_journal.index') }}" class="text-yellow-700 underline">Back to Journal</a>
                <button type="submit" class="bg-yellow-700 hover:bg-yellow-800 text-white font-bold py-2 px-4 rounded">Post Entry</button>
            </div>
        </form>
    </div>
</div>
@endsection 