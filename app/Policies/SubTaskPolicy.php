<?php

namespace App\Policies;

use App\Models\SubTask;
use App\Models\User;

class SubTaskPolicy
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
    public function view(User $user, SubTask $subTask): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SubTask $subTask): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $subTask->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SubTask $subTask): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $subTask->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SubTask $subTask): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $subTask->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SubTask $subTask): bool
    {
        return false;
    }
}
