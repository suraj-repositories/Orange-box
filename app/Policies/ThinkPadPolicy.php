<?php

namespace App\Policies;

use App\Models\ThinkPad;
use App\Models\User;

class ThinkPadPolicy
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
    public function view(User $user, ThinkPad $thinkPad): bool
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
    public function update(User $user, ThinkPad $thinkPad): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $thinkPad->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ThinkPad $thinkPad): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $thinkPad->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ThinkPad $thinkPad): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $thinkPad->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ThinkPad $thinkPad): bool
    {
        return false;
    }
}
