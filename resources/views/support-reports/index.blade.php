@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Support Reports</h1>
                    <p class="text-gray-600">Resources and support for your mental health journey</p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500">Total Reports</div>
                    <div class="text-2xl font-bold text-blue-600">{{ $supportReports->total() }}</div>
                </div>
            </div>
        </div>

        @if(session('status'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('status') }}
            </div>
        @endif

        @if($supportReports->count() > 0)
            <div class="space-y-6">
                @foreach($supportReports as $report)
                    <div class="bg-white rounded-lg shadow-md p-6 {{ !$report->is_read ? 'border-l-4 border-red-500' : '' }}">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    @if(!$report->is_read)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            New
                                        </span>
                                    @endif
                                    <span class="text-sm text-gray-500">
                                        {{ $report->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                    Support Resources Available
                                </h3>
                                
                                <div class="mb-4">
                                    <h4 class="font-medium text-gray-700 mb-2">Keywords Detected:</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($report->keywords_detected as $category => $data)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ ucfirst(str_replace('_', ' ', $category)) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h4 class="font-medium text-gray-700 mb-2">Related Journal Entry:</h4>
                                    <p class="text-gray-600 text-sm">
                                        {{ Str::limit($report->moodJournal->content, 150) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('support-reports.show', $report->id) }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                                    View Resources
                                </a>
                                @if(!$report->is_read)
                                    <form action="{{ route('support-reports.read', $report->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-gray-600 hover:text-gray-800 text-sm">
                                            Mark as Read
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <form action="{{ route('support-reports.destroy', $report->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this support report?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($supportReports->hasPages())
                <div class="mt-8">
                    {{ $supportReports->links() }}
                </div>
            @endif
        @else
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <div class="text-green-500 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Support Reports</h3>
                <p class="text-gray-600 mb-4">Great! You haven't triggered any support reports yet.</p>
                <p class="text-sm text-gray-500">
                    Support reports are automatically generated when concerning keywords are detected in your journal entries.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection 