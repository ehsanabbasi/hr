<?php

namespace App\Http\Controllers;

use App\Models\LeaveReason;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $leaveRequests = $user->leaveRequests()->with(['leaveReason', 'approver'])->latest()->paginate(10);
        
        return view('leave-requests.index', compact('leaveRequests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $leaveReasons = LeaveReason::where('active', true)->get();
        return view('leave-requests.create', compact('leaveReasons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_reason_id' => 'required|exists:leave_reasons,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'note' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        LeaveRequest::create($validated);

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        $this->authorize('view', $leaveRequest);
        
        $leaveRequest->load(['leaveReason', 'approver']);
        return view('leave-requests.show', compact('leaveRequest'));
    }

    /**
     * Cancel a leave request (only if it's pending).
     */
    public function cancel(LeaveRequest $leaveRequest)
    {
        $this->authorize('cancel', $leaveRequest);
        
        if (!$leaveRequest->isPending()) {
            return redirect()->route('leave-requests.index')
                ->with('error', 'Only pending leave requests can be cancelled.');
        }
        
        $leaveRequest->delete();
        
        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request cancelled successfully.');
    }
}