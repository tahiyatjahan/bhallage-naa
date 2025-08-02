@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-yellow-900 leading-tight">
        Report Whisper
    </h2>
@endsection

@section('content')
<div class="max-w-xl mx-auto py-8">
    <div class="bg-yellow-100 p-6 rounded shadow">
        <h3 class="text-lg font-bold mb-4 text-yellow-900">Reporting this Whisper:</h3>
        <div class="mb-4 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">{{ $whisper->content }}</div>
        <form method="POST" action="{{ route('whispers.report.store', $whisper->id) }}">
            @csrf
            <div class="mb-4">
                <label class="block text-yellow-900 font-bold mb-2">Reason for reporting</label>
                <textarea name="reason" rows="3" class="w-full border border-yellow-400 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-yellow-500" maxlength="500" required>{{ old('reason') }}</textarea>
                @error('reason')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="flex justify-between items-center">
                <a href="{{ route('whispers.index') }}" class="text-yellow-700 underline">Back to Whispers</a>
                <button type="submit" class="bg-white hover:bg-gray-200 text-black font-bold py-3 px-6 rounded shadow-lg border-2 border-black text-lg">Submit Report</button>
            </div>
        </form>
    </div>
</div>
@endsection 