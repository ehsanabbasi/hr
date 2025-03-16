<?php

namespace App\Policies;

use App\Models\CareerOpportunity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CareerOpportunityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any career opportunities.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view career opportunities
    }

    /**
     * Determine whether the user can view the career opportunity.
     */
    public function view(User $user, CareerOpportunity $careerOpportunity): bool
    {
        return true; // All authenticated users can view career opportunity details
    }

    /**
     * Determine whether the user can create career opportunities.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the career opportunity.
     */
    public function update(User $user, CareerOpportunity $careerOpportunity): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the career opportunity.
     */
    public function delete(User $user, CareerOpportunity $careerOpportunity): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can view candidates for the career opportunity.
     */
    public function viewCandidates(User $user, CareerOpportunity $careerOpportunity): bool
    {
        return $user->hasRole('admin') || $careerOpportunity->reviewers->contains($user);
    }

    /**
     * Determine whether the user can create candidates for the career opportunity.
     */
    public function createCandidate(User $user, CareerOpportunity $careerOpportunity): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update candidates for the career opportunity.
     */
    public function updateCandidate(User $user, CareerOpportunity $careerOpportunity): bool
    {
        return $user->hasRole('admin') || $careerOpportunity->reviewers->contains($user);
    }

    /**
     * Determine whether the user can delete candidates for the career opportunity.
     */
    public function deleteCandidate(User $user, CareerOpportunity $careerOpportunity): bool
    {
        return $user->hasRole('admin');
    }
}
