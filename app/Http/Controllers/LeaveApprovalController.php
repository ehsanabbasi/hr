<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveApprovalController extends Controller
{
   

    /**
     * Display a listing of pending leave requests.
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->isHeadOfDepartment()) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to approve leave requests.');
        }
        
        $pendingRequests = $user->pendingDepartmentLeaveRequests()
            ->with(['user', 'leaveReason'])
            ->latest()
            ->paginate(10);
        
        return view('leave-approvals.index', compact('pendingRequests'));
    }
    
    /**
     * Show a specific leave request.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        $this->authorize('approve', $leaveRequest);
        
        $leaveRequest->load(['user', 'leaveReason']);
        return view('leave-approvals.show', compact('leaveRequest'));
    }
    
    /**
     * Process a leave request (approve or reject).
     */
    public function process(Request $request, LeaveRequest $leaveRequest)
    {
        $this->authorize('approve', $leaveRequest);
        
        $validated = $request->validate([
            'action' => 'required|in:approve,reject',
            'rejection_reason' => 'required_if:action,reject|nullable|string',
        ]);
        
        if (!$leaveRequest->isPending()) {
            return redirect()->route('leave-approvals.index')
                ->with('error', 'This leave request has already been processed.');
        }
        
        $leaveRequest->status = $validated['action'] === 'approve' ? 'approved' : 'rejected';
        $leaveRequest->approved_by = Auth::id();
        $leaveRequest->processed_at = now();
        
        if ($validated['action'] === 'reject') {
            $leaveRequest->rejection_reason = $validated['rejection_reason'];
        }
        
        $leaveRequest->save();
        
        $actionText = $validated['action'] === 'approve' ? 'approved' : 'rejected';
        
        return redirect()->route('leave-approvals.index')
            ->with('success', "Leave request {$actionText} successfully.");
    }
}