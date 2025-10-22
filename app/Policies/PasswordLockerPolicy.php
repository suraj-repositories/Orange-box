<?php

namespace App\Policies;

use App\Models\PasswordLocker;
use App\Models\User;

class PasswordLockerPolicy
{
     /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PasswordLocker $passwordLocker): bool
    {
        return $passwordLocker->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PasswordLocker $passwordLocker): bool
    {
        return $passwordLocker->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PasswordLocker $passwordLocker): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $passwordLocker->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PasswordLocker $passwordLocker): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $passwordLocker->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PasswordLocker $passwordLocker): bool
    {
        return false;
    }
}
