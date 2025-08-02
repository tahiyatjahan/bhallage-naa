<?php

namespace App\Http\Controllers;

use App\Models\SupportReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportReportController extends Controller
{
    /**
     * Display a listing of support reports for the authenticated user
     */
    public function index()
    {
        $supportReports = Auth::user()->supportReports()
            ->with(['moodJournal'])
            ->latest()
            ->paginate(10);

        return view('support-reports.index', compact('supportReports'));
    }

    /**
     * Display the specified support report
     */
    public function show($id)
    {
        $supportReport = SupportReport::with(['moodJournal'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Mark as read
        if (!$supportReport->is_read) {
            $supportReport->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        }

        return view('support-reports.show', compact('supportReport'));
    }

    /**
     * Mark a support report as read
     */
    public function markAsRead($id)
    {
        $supportReport = SupportReport::where('user_id', Auth::id())
            ->findOrFail($id);

        $supportReport->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        return back()->with('status', 'Support report marked as read.');
    }

    /**
     * Delete a support report
     */
    public function destroy($id)
    {
        $supportReport = SupportReport::where('user_id', Auth::id())
            ->findOrFail($id);

        $supportReport->delete();

        return redirect()->route('support-reports.index')
                        ->with('status', 'Support report deleted successfully.');
    }
}
