<?php

namespace App\Http\Controllers;

use App\Models\LeaveReason;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    public function index(Request $request)
    {
        // Get search and filter parameters
        $search = $request->input('search');
        $status = $request->input('status');
        $leaveType = $request->input('leave_type');
        
        // Start query with user relationship
        $leaveRequestsQuery = LeaveRequest::with(['user', 'leaveReason']);
        
        // Apply search if provided
        if ($search) {
            $leaveRequestsQuery->whereHas('user', function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Apply status filter if provided
        if ($status) {
            $leaveRequestsQuery->where('status', $status);
        }
        
        // Apply leave type filter if provided
        if ($leaveType) {
            $leaveRequestsQuery->whereHas('leaveReason', function($query) use ($leaveType) {
                $query->where('name', 'like', "%{$leaveType}%");
            });
        }
        
        // For non-admin users, show only their own leave requests
        if (!auth()->user()->can('manage_leave_requests')) {
            $leaveRequestsQuery->where('user_id', auth()->id());
        }
        
        // Get paginated results
        $leaveRequests = $leaveRequestsQuery
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
        
        // Get all active leave reasons for the filter dropdown
        $leaveReasons = LeaveReason::where('active', true)->get();
        
        return view('leave-requests.index', compact('leaveRequests', 'search', 'status', 'leaveType', 'leaveReasons'));
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
     * Approve a leave request.
     */
    public function approve(LeaveRequest $leaveRequest)
    {
        $this->authorize('manage_leave_requests');
        
        if (!$leaveRequest->isPending()) {
            return redirect()->route('leave-requests.index')
                ->with('error', 'Only pending leave requests can be approved.');
        }
        
        $leaveRequest->status = 'approved';
        $leaveRequest->approved_by = Auth::id();
        $leaveRequest->processed_at = now();
        $leaveRequest->save();
        
        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request approved successfully.');
    }
    
    /**
     * Reject a leave request.
     */
    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $this->authorize('manage_leave_requests');
        
        if (!$leaveRequest->isPending()) {
            return redirect()->route('leave-requests.index')
                ->with('error', 'Only pending leave requests can be rejected.');
        }
        
        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:255',
        ]);
        
        $leaveRequest->status = 'rejected';
        $leaveRequest->approved_by = Auth::id();
        $leaveRequest->processed_at = now();
        $leaveRequest->rejection_reason = $validated['rejection_reason'] ?? null;
        $leaveRequest->save();
        
        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request rejected successfully.');
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
        
        $leaveRequest->status = 'cancelled';
        $leaveRequest->save();
        
        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request cancelled successfully.');
    }
    
    /**
     * Delete a leave request.
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
        if (auth()->user()->can('manage_leave_requests') || auth()->id() == $leaveRequest->user_id) {
            $leaveRequest->delete();
            
            return redirect()->route('leave-requests.index')
                ->with('success', 'Leave request deleted successfully.');
        }
        
        return redirect()->route('leave-requests.index')
            ->with('error', 'You do not have permission to delete this leave request.');
    }
}