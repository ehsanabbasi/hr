<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserCertificate;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserCertificatePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, User $targetUser): bool
    {
        // User can view their own certificates or admin/hr can view any user's certificates
        return $user->id === $targetUser->id || $user->hasRole(['admin', 'hr']);
    }

    public function view(User $user, UserCertificate $userCertificate): bool
    {
        // User can view their own certificates or admin/hr can view any certificate
        return $user->id === $userCertificate->user_id || $user->hasRole(['admin', 'hr']);
    }

    public function upload(User $user, UserCertificate $userCertificate): bool
    {
        // User can only upload to their own certificates
        return $user->id === $userCertificate->user_id;
    }

    public function delete(User $user, UserCertificate $userCertificate): bool
    {
        // Only admin or HR can delete certificates
        return $user->hasRole(['admin', 'hr']);
    }
} 