<?php

namespace App\Policies;

use App\Models\FacilityNeed;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FacilityNeedPolicy
{
    use HandlesAuthorization;

    public function view(User $user, FacilityNeed $facilityNeed)
    {
        // HR and admin can view all facility needs
        if ($user->hasRole(['hr', 'admin'])) {
            return true;
        }
        
        // Users can only view their own facility needs
        return $user->id === $facilityNeed->user_id;
    }

    public function create(User $user)
    {
        // All authenticated users can create facility needs
        return true;
    }

    public function update(User $user, FacilityNeed $facilityNeed)
    {
        // Only the owner can update their facility need, and only if it's pending
        return $user->id === $facilityNeed->user_id && $facilityNeed->status === 'pending';
    }

    public function updateStatus(User $user, FacilityNeed $facilityNeed)
    {
        // Only HR and admin can update status
        return $user->hasRole(['hr', 'admin']);
    }

    public function delete(User $user, FacilityNeed $facilityNeed)
    {
        // HR and admin can delete any facility need
        if ($user->hasRole(['hr', 'admin'])) {
            return true;
        }
        
        // Users can only delete their own facility needs if they're still pending
        return $user->id === $facilityNeed->user_id && $facilityNeed->status === 'pending';
    }
} 