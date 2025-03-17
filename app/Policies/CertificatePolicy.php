<?php

namespace App\Policies;

use App\Models\Certificate;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CertificatePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'hr']);
    }

    public function view(User $user, Certificate $certificate): bool
    {
        return $user->hasRole(['admin', 'hr']);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'hr']);
    }

    public function update(User $user, Certificate $certificate): bool
    {
        return $user->hasRole(['admin', 'hr']);
    }

    public function delete(User $user, Certificate $certificate): bool
    {
        return $user->hasRole(['admin', 'hr']);
    }

    public function assign(User $user): bool
    {
        return $user->hasRole(['admin', 'hr']);
    }
} 