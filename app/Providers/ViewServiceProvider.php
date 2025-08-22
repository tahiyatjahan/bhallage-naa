<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\SupportReport;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share unread counts with navigation
        View::composer('layouts.navigation', function ($view) {
            if (Auth::check()) {
                $unreadSupportReports = SupportReport::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->count();
                
                $unreadNotifications = Notification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->count();
                
                $view->with(compact('unreadSupportReports', 'unreadNotifications'));
            } else {
                $view->with(compact('unreadSupportReports', 'unreadNotifications'));
            }
        });

        // Share unread counts with home page
        View::composer('home', function ($view) {
            if (Auth::check()) {
                $unreadSupportReports = SupportReport::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->count();
                
                $unreadNotifications = Notification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->count();
                
                $view->with(compact('unreadSupportReports', 'unreadNotifications'));
            } else {
                $view->with(compact('unreadSupportReports', 'unreadNotifications'));
            }
        });
    }
}
