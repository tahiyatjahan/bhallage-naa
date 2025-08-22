@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="text-5xl mb-4">🔔</div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Notifications</h1>
            <p class="text-gray-600">Stay updated with all your activity</p>
        </div>

        <!-- Actions Bar -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 p-6 mb-8 actions-bar">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">
                        <span class="font-semibold">{{ $unreadCount }}</span> unread notifications
                    </span>
                    @if($unreadCount > 0)
                        <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    @endif
                </div>
                <div class="flex space-x-3">
                    @if($unreadCount > 0)
                        <button onclick="markAllAsRead()" 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                            <span class="mr-2">✓</span>
                            Mark All Read
                        </button>
                    @endif
                    <a href="{{ route('home') }}" 
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        Back to Home
                    </a>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        @if($notifications->count() > 0)
            <div class="space-y-4">
                @foreach($notifications as $notification)
                    <div class="notification-card {{ $notification->is_read ? 'read' : 'unread' }}" 
                         id="notification-{{ $notification->id }}">
                        <!-- Notification Header -->
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl {{ $notification->color_class }}">
                                    {{ $notification->icon }}
                                </div>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 {{ $notification->is_read ? '' : 'font-bold' }}">
                                        {{ $notification->title }}
                                    </h3>
                                    <div class="flex items-center space-x-2">
                                        @if(!$notification->is_read)
                                            <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                                        @endif
                                        <span class="text-sm text-gray-500">{{ $notification->time_ago }}</span>
                                    </div>
                                </div>
                                
                                <p class="text-gray-700 mb-3 {{ $notification->is_read ? '' : 'font-medium' }}">
                                    {{ $notification->message }}
                                </p>
                                
                                <!-- From User Info -->
                                @if($notification->fromUser)
                                    <div class="flex items-center space-x-2 mb-3">
                                        <div class="w-6 h-6 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center text-white text-xs font-bold">
                                            {{ substr($notification->fromUser->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm text-gray-600">from {{ $notification->fromUser->name }}</span>
                                    </div>
                                @endif
                                
                                <!-- Content Type Info -->
                                <div class="flex items-center space-x-2 mb-3">
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                        {{ $notification->notifiable_type }} #{{ $notification->notifiable_id }}
                                    </span>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="flex items-center space-x-3">
                                    @if($notification->action_url)
                                        <a href="{{ $notification->action_url }}" 
                                           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                            View Details
                                        </a>
                                    @endif
                                    
                                    @if(!$notification->is_read)
                                        <button onclick="markAsRead({{ $notification->id }})" 
                                                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                            Mark Read
                                        </button>
                                    @endif
                                    
                                    <button onclick="deleteNotification({{ $notification->id }})" 
                                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $notifications->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📭</div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No notifications yet</h3>
                <p class="text-gray-600">You'll see notifications here when someone interacts with your content</p>
            </div>
        @endif
    </div>
</div>

<style>
    .notification-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .notification-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
    }
    
    .notification-card.unread {
        border-left-color: #3B82F6;
        background: linear-gradient(135deg, #F0F9FF 0%, #FFFFFF 100%);
    }
    
    .notification-card.read {
        opacity: 0.8;
        background: #F9FAFB;
    }
    
    .notification-card.read:hover {
        opacity: 1;
        background: white;
    }
</style>

<script>
    function markAsRead(notificationId) {
        const notification = document.getElementById(`notification-${notificationId}`);
        const markReadButton = notification.querySelector('button[onclick*="markAsRead"]');
        
        // Show loading state
        if (markReadButton) {
            const originalText = markReadButton.textContent;
            markReadButton.disabled = true;
            markReadButton.textContent = 'Marking...';
        }
        
        fetch(`/notifications/${notificationId}/mark-read`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Immediately update the notification appearance
                notification.classList.remove('unread');
                notification.classList.add('read');
                
                // Remove the unread indicator dot
                const unreadDot = notification.querySelector('.w-3.h-3.bg-blue-500');
                if (unreadDot) {
                    unreadDot.remove();
                }
                
                // Hide the "Mark Read" button since it's now read
                if (markReadButton) {
                    markReadButton.style.display = 'none';
                }
                
                // Update unread count
                updateUnreadCount();
                
                // Show success feedback
                showSuccessMessage('Notification marked as read');
            } else {
                showErrorMessage('Failed to mark notification as read');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage('Error marking notification as read');
        })
        .finally(() => {
            // Reset button state
            if (markReadButton) {
                markReadButton.disabled = false;
                markReadButton.textContent = originalText;
            }
        });
    }
    
    function markAllAsRead() {
        const markAllButton = document.querySelector('button[onclick="markAllAsRead()"]');
        const originalText = markAllButton.textContent;
        
        // Show loading state
        markAllButton.disabled = true;
        markAllButton.textContent = 'Marking All...';
        
        fetch('/notifications/mark-all-read', {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mark all notifications as read visually
                document.querySelectorAll('.notification-card.unread').forEach(card => {
                    card.classList.remove('unread');
                    card.classList.add('read');
                    
                    // Remove unread indicator dots
                    const unreadDot = card.querySelector('.w-3.h-3.bg-blue-500');
                    if (unreadDot) {
                        unreadDot.remove();
                    }
                    
                    // Hide "Mark Read" buttons
                    const markReadButton = card.querySelector('button[onclick*="markAsRead"]');
                    if (markReadButton) {
                        markReadButton.style.display = 'none';
                    }
                });
                
                // Update unread count
                updateUnreadCount();
                
                // Hide the "Mark All Read" button since all are now read
                markAllButton.style.display = 'none';
                
                // Show success feedback
                showSuccessMessage(`Marked ${data.message.split(' ')[1]} notifications as read`);
            } else {
                showErrorMessage('Failed to mark all notifications as read');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage('Error marking all notifications as read');
        })
        .finally(() => {
            // Reset button state
            markAllButton.disabled = false;
            markAllButton.textContent = originalText;
        });
    }
    
    function deleteNotification(notificationId) {
        if (!confirm('Are you sure you want to delete this notification?')) {
            return;
        }
        
        const notification = document.getElementById(`notification-${notificationId}`);
        const deleteButton = notification.querySelector('button[onclick*="deleteNotification"]');
        
        // Show loading state
        if (deleteButton) {
            const originalText = deleteButton.textContent;
            deleteButton.disabled = true;
            deleteButton.textContent = 'Deleting...';
        }
        
        fetch(`/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Smooth slide out animation
                notification.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => {
                    notification.remove();
                    updateUnreadCount();
                    showSuccessMessage('Notification deleted successfully');
                }, 300);
            } else {
                showErrorMessage('Failed to delete notification');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage('Error deleting notification');
        })
        .finally(() => {
            // Reset button state
            if (deleteButton) {
                deleteButton.disabled = false;
                deleteButton.textContent = originalText;
            }
        });
    }
    
    function updateUnreadCount() {
        fetch('/notifications/unread/count')
            .then(response => response.json())
            .then(data => {
                const countElement = document.querySelector('.font-semibold');
                if (countElement) {
                    countElement.textContent = data.count;
                }
                
                // Update the unread count in the actions bar
                const actionsBarCount = document.querySelector('.actions-bar .font-semibold');
                if (actionsBarCount) {
                    actionsBarCount.textContent = data.count;
                }
                
                // Hide the "Mark All Read" button if no unread notifications
                if (data.count === 0) {
                    const markAllButton = document.querySelector('button[onclick="markAllAsRead()"]');
                    if (markAllButton) {
                        markAllButton.style.display = 'none';
                    }
                }
            });
    }
    
    function showSuccessMessage(message) {
        showMessage(message, 'success');
    }
    
    function showErrorMessage(message) {
        showMessage(message, 'error');
    }
    
    function showMessage(message, type) {
        // Remove existing messages
        const existingMessage = document.querySelector('.message-toast');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        // Create message element
        const messageDiv = document.createElement('div');
        messageDiv.className = `message-toast fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
        messageDiv.style.zIndex = '9999';
        
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        const icon = type === 'success' ? '✅' : '❌';
        
        messageDiv.innerHTML = `
            <div class="flex items-center space-x-2 text-white">
                <span class="text-lg">${icon}</span>
                <span class="font-medium">${message}</span>
            </div>
        `;
        
        messageDiv.className = `message-toast fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full ${bgColor}`;
        
        document.body.appendChild(messageDiv);
        
        // Animate in
        setTimeout(() => {
            messageDiv.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            messageDiv.classList.add('translate-x-full');
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.remove();
                }
            }, 300);
        }, 3000);
    }
</script>

<style>
    @keyframes slideOut {
        0% { transform: translateX(0); opacity: 1; }
        100% { transform: translateX(100%); opacity: 0; }
    }
</style>
@endsection
