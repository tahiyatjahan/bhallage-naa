<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\SupportReport;
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
        // Share unread support reports count with navigation
        View::composer('layouts.navigation', function ($view) {
            if (Auth::check()) {
                $unreadSupportReports = SupportReport::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->count();
                
                $view->with('unreadSupportReports', $unreadSupportReports);
            } else {
                $view->with('unreadSupportReports', 0);
            }
        });

        // Share unread support reports count with home page
        View::composer('home', function ($view) {
            if (Auth::check()) {
                $unreadSupportReports = SupportReport::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->count();
                
                $view->with('unreadSupportReports', $unreadSupportReports);
            } else {
                $view->with('unreadSupportReports', 0);
            }
        });
    }
}
