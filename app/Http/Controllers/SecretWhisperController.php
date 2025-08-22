<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Whisper;
use App\Models\WhisperReport;
use App\Services\NotificationService;

class SecretWhisperController extends Controller
{
    public function index()
    {
        $whispers = Whisper::latest()->get();
        return view('whispers.index', compact('whispers'));
    }

    public function create()
    {
        return view('whispers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        Whisper::create([
            'content' => $request->content,
            'user_id' => auth()->id(),
        ]);
        return redirect()->route('whispers.index')->with('status', 'Your secret whisper has been posted!');
    }

    public function report($id)
    {
        $whisper = Whisper::findOrFail($id);
        return view('whispers.report', compact('whisper'));
    }

    public function storeReport(Request $request, $id)
    {
        $whisper = Whisper::findOrFail($id);
        
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);
        
        WhisperReport::create([
            'whisper_id' => $id,
            'reason' => $request->reason,
        ]);

        // Create notification for all admins
        NotificationService::report(
            reportableType: 'Whisper',
            reportableId: $id,
            reportReason: $request->reason,
            actionUrl: route('admin.reports')
        );

        return redirect()->route('whispers.index')->with('status', 'Thank you for reporting this whisper.');
    }

    /**
     * Toggle highlight status of a whisper (admin only)
     */
    public function toggleHighlight($id)
    {
        $whisper = Whisper::findOrFail($id);
        $whisper->is_highlighted = !$whisper->is_highlighted;
        $whisper->save();
        
        $status = $whisper->is_highlighted ? 'highlighted' : 'unhighlighted';
        return back()->with('status', "Whisper {$status} successfully.");
    }
}
