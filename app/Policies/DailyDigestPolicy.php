<?php

namespace App\Policies;

use App\Models\DailyDigest;
use App\Models\User;

class DailyDigestPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

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
    public function view(User $user, DailyDigest $dailyDigest): bool
    {
        return true;
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
    public function update(User $user, DailyDigest $dailyDigest): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $dailyDigest->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DailyDigest $dailyDigest): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $dailyDigest->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DailyDigest $dailyDigest): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $dailyDigest->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DailyDigest $dailyDigest): bool
    {
        return false;
    }
}
