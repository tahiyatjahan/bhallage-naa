<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WhisperReport;
use App\Models\Whisper;
use App\Models\User;
use App\Models\MoodJournal;
use App\Models\DailyPrompt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard()
    {
        $reports = WhisperReport::with('whisper')->latest()->get();
        $totalUsers = User::count();
        $totalWhispers = Whisper::count();
        $totalMoodJournals = MoodJournal::count();
        $pendingReports = WhisperReport::count(); // All reports are considered pending since there's no status column
        $totalDailyPrompts = DailyPrompt::count();
        $activeDailyPrompts = DailyPrompt::where('is_active', true)->count();
        
        return view('admin.dashboard', compact('reports', 'totalUsers', 'totalWhispers', 'totalMoodJournals', 'pendingReports', 'totalDailyPrompts', 'activeDailyPrompts'));
    }

    public function deleteWhisper($id)
    {
        $whisper = Whisper::findOrFail($id);
        $whisper->delete();
        return redirect()->route('admin.dashboard')->with('status', 'Whisper deleted successfully.');
    }

    public function profile()
    {
        $admin = Auth::user();
        return view('admin.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($admin->id)],
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $imageName = time().'_'.$image->getClientOriginalName();
            $image->move(public_path('profile_pictures'), $imageName);
            $admin->profile_picture = 'profile_pictures/'.$imageName;
        }

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
            $admin->password = Hash::make($validated['new_password']);
        }

        $admin->save();

        return redirect()->route('admin.profile')->with('status', 'Admin profile updated successfully!');
    }

    public function users()
    {
        $users = User::withCount(['moodJournals', 'whispers'])->latest()->paginate(15);
        return view('admin.users', compact('users'));
    }

    public function reports()
    {
        $reports = WhisperReport::with('whisper')->latest()->paginate(15);
        return view('admin.reports', compact('reports'));
    }

    public function toggleAdminStatus($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent admin from removing their own admin status
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot modify your own admin status.');
        }
        
        $user->is_admin = !$user->is_admin;
        $user->save();
        
        $status = $user->is_admin ? 'granted admin privileges' : 'removed admin privileges';
        return back()->with('status', "User {$user->name} has been {$status}.");
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent admin from deleting themselves
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        
        $user->delete();
        return back()->with('status', "User {$user->name} has been deleted successfully.");
    }

    public function statistics()
    {
        $totalUsers = User::count();
        $adminUsers = User::where('is_admin', true)->count();
        $totalWhispers = Whisper::count();
        $totalMoodJournals = MoodJournal::count();
        $pendingReports = WhisperReport::count(); // All reports are considered pending
        $totalDailyPrompts = DailyPrompt::count();
        $activeDailyPrompts = DailyPrompt::where('is_active', true)->count();
        
        // Monthly user registrations
        $monthlyUsers = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Recent activity
        $recentUsers = User::latest()->take(5)->get();
        $recentWhispers = Whisper::latest()->take(5)->get();
        $recentMoodJournals = MoodJournal::latest()->take(5)->get();
        $recentDailyPrompts = DailyPrompt::latest()->take(5)->get();
        
        return view('admin.statistics', compact(
            'totalUsers', 'adminUsers', 'totalWhispers', 'totalMoodJournals', 
            'pendingReports', 'totalDailyPrompts', 'activeDailyPrompts',
            'monthlyUsers', 'recentUsers', 'recentWhispers', 'recentMoodJournals', 'recentDailyPrompts'
        ));
    }
}
