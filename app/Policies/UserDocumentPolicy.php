<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserDocumentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, User $targetUser)
    {
        // User can view their own documents or admin can view any user's documents
        return $user->id === $targetUser->id || $user->hasRole(['admin', 'hr']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserDocument $document)
    {
        // User can view their own documents or admin can view any document
        return $user->id === $document->user_id || $user->hasRole(['admin', 'hr']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, User $targetUser)
    {
        // User can upload to their own profile or admin can upload to any profile
        return $user->id === $targetUser->id || $user->hasRole(['admin', 'hr']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserDocument $document)
    {
        // Only admin or the uploader can update documents
        return $user->id === $document->uploaded_by || $user->hasRole(['admin', 'hr']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserDocument $document)
    {
        // Only admin or the uploader can delete documents
        return $user->id === $document->uploaded_by || $user->hasRole(['admin', 'hr']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserDocument $userDocument): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserDocument $userDocument): bool
    {
        return false;
    }
}
