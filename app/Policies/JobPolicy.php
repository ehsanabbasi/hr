<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any jobs.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view jobs
    }

    /**
     * Determine whether the user can view the job.
     */
    public function view(User $user, Job $job): bool
    {
        return true; // All authenticated users can view job details
    }

    /**
     * Determine whether the user can create jobs.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the job.
     */
    public function update(User $user, Job $job): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the job.
     */
    public function delete(User $user, Job $job): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view candidates for the job.
     */
    public function viewCandidates(User $user, Job $job): bool
    {
        return $user->hasRole('admin') || $job->reviewers->contains($user);
    }

    /**
     * Determine whether the user can create candidates for the job.
     */
    public function createCandidate(User $user, Job $job): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update candidates for the job.
     */
    public function updateCandidate(User $user, Job $job): bool
    {
        return $user->hasRole('admin') || $job->reviewers->contains($user);
    }

    /**
     * Determine whether the user can delete candidates for the job.
     */
    public function deleteCandidate(User $user, Job $job): bool
    {
        return $user->hasRole('admin');
    }
}