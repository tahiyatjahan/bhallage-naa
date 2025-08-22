@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-8">
    <div class="max-w-3xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="text-5xl mb-4">{{ $notification->icon }}</div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Notification Details</h1>
            <p class="text-gray-600">View the full details of this notification</p>
        </div>

        <!-- Notification Card -->
        <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
            <!-- Notification Header -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-8 py-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold">{{ $notification->title }}</h2>
                        <p class="text-blue-100 mt-1">{{ $notification->time_ago }}</p>
                    </div>
                    <div class="text-4xl">{{ $notification->icon }}</div>
                </div>
            </div>

            <!-- Notification Content -->
            <div class="p-8">
                <div class="space-y-6">
                    <!-- Message -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Message</h3>
                        <p class="text-gray-700 text-lg leading-relaxed">{{ $notification->message }}</p>
                    </div>

                    <!-- From User -->
                    @if($notification->fromUser)
                        <div class="bg-gray-50 rounded-2xl p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">From User</h3>
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center text-white text-2xl font-bold">
                                    {{ substr($notification->fromUser->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-xl font-semibold text-gray-900">{{ $notification->fromUser->name }}</h4>
                                    <p class="text-gray-600">User ID: {{ $notification->fromUser->id }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Notification Type -->
                    <div class="bg-gray-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Notification Type</h3>
                        <div class="flex items-center space-x-3">
                            <span class="text-2xl {{ $notification->color_class }}">{{ $notification->icon }}</span>
                            <div>
                                <p class="text-lg font-medium text-gray-900">{{ ucfirst($notification->type) }}</p>
                                <p class="text-gray-600">Type: {{ $notification->type }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Related Content -->
                    <div class="bg-gray-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Related Content</h3>
                        <div class="bg-white rounded-xl p-4 border border-gray-200">
                            <p class="text-gray-700">
                                <strong>Type:</strong> {{ $notification->notifiable_type }}<br>
                                <strong>ID:</strong> {{ $notification->notifiable_id }}
                            </p>
                            @if($notification->action_url)
                                <a href="{{ $notification->action_url }}" 
                                   class="inline-block mt-3 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                    View Related Content
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Metadata -->
                    @if($notification->metadata)
                        <div class="bg-gray-50 rounded-2xl p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Additional Information</h3>
                            <div class="bg-white rounded-xl p-4 border border-gray-200">
                                <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ json_encode($notification->metadata, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    @endif

                    <!-- Status -->
                    <div class="bg-gray-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Status</h3>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                @if($notification->is_read)
                                    <span class="w-4 h-4 bg-green-500 rounded-full"></span>
                                    <span class="text-green-700 font-medium">Read</span>
                                    @if($notification->read_at)
                                        <span class="text-gray-500 text-sm">at {{ $notification->read_at->format('M d, Y g:i A') }}</span>
                                    @endif
                                @else
                                    <span class="w-4 h-4 bg-blue-500 rounded-full animate-pulse"></span>
                                    <span class="text-blue-700 font-medium">Unread</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                    <div class="flex space-x-3">
                        @if(!$notification->is_read)
                            <button onclick="markAsRead()" 
                                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl font-medium transition-colors">
                                Mark as Read
                            </button>
                        @endif
                        
                        <button onclick="deleteNotification()" 
                                class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-xl font-medium transition-colors">
                            Delete Notification
                        </button>
                    </div>
                    
                    <a href="{{ route('notifications.index') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-medium transition-colors">
                        Back to Notifications
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function markAsRead() {
        fetch(`/notifications/{{ $notification->id }}/mark-read`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function deleteNotification() {
        if (!confirm('Are you sure you want to delete this notification?')) {
            return;
        }
        
        fetch(`/notifications/{{ $notification->id }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("notifications.index") }}';
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endsection
