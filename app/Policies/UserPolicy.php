<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $targetUser)
    {
        return $user->hasRole(['admin', 'hr']) || $user->id === $targetUser->id;
    }

    /**
     * Determine whether the user can view onboarding tasks for the model.
     */
    public function viewOnboarding(User $user, User $targetUser)
    {
        // Admin can view anyone's onboarding, users can only view their own
        return $user->hasRole(['admin', 'hr']) || $user->id === $targetUser->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $targetUser)
    {
        return $user->hasRole(['admin', 'hr']) || $user->id === $targetUser->id;
    }
}