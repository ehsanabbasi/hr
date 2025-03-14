<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkingHour;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkingHourPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view their own working hours
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WorkingHour $workingHour): bool
    {
        // Users can view their own working hours
        return $user->id === $workingHour->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All authenticated users can create working hours
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WorkingHour $workingHour): bool
    {
        // Only the owner can update their working hours
        return $user->id === $workingHour->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WorkingHour $workingHour): bool
    {
        // Only the owner can delete their working hours
        return $user->id === $workingHour->user_id;
    }
} 