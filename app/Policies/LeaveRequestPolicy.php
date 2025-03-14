<?php

namespace App\Policies;

use App\Models\LeaveRequest;
use App\Models\User;

class LeaveRequestPolicy
{
    /**
     * Determine if the user can view the leave request.
     */
    public function view(User $user, LeaveRequest $leaveRequest): bool
    {
        // Users can view their own leave requests
        if ($user->id === $leaveRequest->user_id && $user->can('view own leave requests')) {
            return true;
        }
        
        // Department heads can view leave requests from their department
        if ($user->can('view department leave requests') && $user->isHeadOfDepartment()) {
            $departmentIds = $user->headOfDepartments()->pluck('id');
            return $leaveRequest->user->department_id && in_array($leaveRequest->user->department_id, $departmentIds->toArray());
        }
        
        return false;
    }
    
    public function cancel(User $user, LeaveRequest $leaveRequest): bool
    {
        // Users can only cancel their own pending leave requests
        return $user->can('cancel own leave requests') && 
               $user->id === $leaveRequest->user_id && 
               $leaveRequest->isPending();
    }
    
    /**
     * Determine if the user can approve/reject the leave request.
     */
    public function approve(User $user, LeaveRequest $leaveRequest): bool
    {
        // Only department heads can approve leave requests
        if (!$user->can('approve leave requests') || !$user->isHeadOfDepartment()) {
            return false;
        }
        
        // Department heads can only approve leave requests from their department
        $departmentIds = $user->headOfDepartments()->pluck('id');
        return $leaveRequest->user->department_id && in_array($leaveRequest->user->department_id, $departmentIds->toArray());
    }
}