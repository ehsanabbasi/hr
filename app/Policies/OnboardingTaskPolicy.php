<?php

namespace App\Policies;

use App\Models\OnboardingTask;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OnboardingTaskPolicy
{
    use HandlesAuthorization;

    public function view(User $user, OnboardingTask $task)
    {
        // Admin can view all tasks, users can only view their own
        return $user->hasRole(['admin', 'hr']) || $user->id === $task->user_id;
    }

    public function create(User $user)
    {
        // Only admin can create tasks
        return $user->hasRole(['admin', 'hr']);
    }

    public function update(User $user, OnboardingTask $task)
    {
        // Only admin can update task details
        return $user->hasRole(['admin', 'hr']);
    }

    public function updateStatus(User $user, OnboardingTask $task)
    {
        // Admin can update any task status, users can only update their own
        return $user->hasRole(['admin', 'hr']) || $user->id === $task->user_id;
    }

    public function delete(User $user, OnboardingTask $task)
    {
        // Only admin can delete tasks
        return $user->hasRole(['admin', 'hr']);
    }
}