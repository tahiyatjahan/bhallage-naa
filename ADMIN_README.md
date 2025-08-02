# Admin Profile System

This Laravel application now includes a comprehensive admin profile system with the following features:

## Features

### 1. Admin Dashboard
- **Location**: `/admin`
- **Features**:
  - Overview statistics (total users, whispers, mood journals, pending reports)
  - Quick action cards for navigation
  - Recent reports display
  - Modern, responsive design

### 2. Admin Profile Management
- **Location**: `/admin/profile`
- **Features**:
  - Update personal information (name, email)
  - Upload/change profile picture
  - Change password with current password verification
  - Admin status indicator
  - Form validation and error handling

### 3. User Management
- **Location**: `/admin/users`
- **Features**:
  - View all users with pagination
  - User activity statistics (mood journals, whispers count)
  - Grant/remove admin privileges
  - Delete user accounts
  - Search functionality
  - Protection against self-modification

### 4. Statistics & Analytics
- **Location**: `/admin/statistics`
- **Features**:
  - Key metrics overview
  - Recent user registrations
  - Recent activity feed
  - Monthly user registration charts
  - Admin user count

### 5. Navigation Integration
- Admin users see additional navigation items:
  - Admin dashboard link
  - Admin tools dropdown menu
  - Admin badge in user profile area

## Security Features

### Admin Middleware
- **File**: `app/Http/Middleware/AdminMiddleware.php`
- **Purpose**: Protects admin routes from unauthorized access
- **Registration**: Automatically registered in `bootstrap/app.php`

### Route Protection
All admin routes are protected by the `admin` middleware:
```php
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin routes here
});
```

### Self-Protection
- Admins cannot modify their own admin status
- Admins cannot delete their own accounts
- Proper validation and confirmation dialogs

## Database Structure

### User Model Updates
- Added `is_admin` boolean field (default: false)
- Added `whispers()` relationship
- Existing `moodJournals()` relationship

### Whisper Model Updates
- Added `user_id` to fillable fields
- Added `user()` relationship
- Added `reports()` relationship

## Setup Instructions

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Make a User Admin
Use the provided Artisan command:
```bash
php artisan user:make-admin user@example.com
```

### 3. Access Admin Panel
- Login with an admin account
- Navigate to `/admin` or use the "Admin" link in the navigation

## Routes

### Admin Routes
- `GET /admin` - Admin dashboard
- `GET /admin/profile` - Admin profile page
- `PATCH /admin/profile` - Update admin profile
- `GET /admin/users` - User management
- `PATCH /admin/users/{id}/toggle-admin` - Toggle admin status
- `DELETE /admin/users/{id}` - Delete user
- `GET /admin/statistics` - Statistics page
- `DELETE /admin/whispers/{id}` - Delete whisper

## Views

### Admin Views Location
- `resources/views/admin/dashboard.blade.php`
- `resources/views/admin/profile.blade.php`
- `resources/views/admin/users.blade.php`
- `resources/views/admin/statistics.blade.php`

### Navigation Updates
- `resources/views/layouts/navigation.blade.php` - Added admin navigation

## Styling

The admin interface uses:
- Tailwind CSS for styling
- Responsive design
- Modern card-based layout
- Consistent color scheme with the main application
- Hover effects and transitions

## Error Handling

- Form validation with error messages
- Success/error flash messages
- Confirmation dialogs for destructive actions
- Graceful handling of missing data

## Future Enhancements

Potential improvements:
- User activity logs
- Advanced filtering and sorting
- Bulk user operations
- Email notifications for admin actions
- Audit trail for admin actions
- Role-based permissions (super admin, moderator, etc.)
- Advanced analytics and reporting
- Export functionality for user data 