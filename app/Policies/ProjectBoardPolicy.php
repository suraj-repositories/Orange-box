<?php

namespace App\Policies;

use App\Models\ProjectBoard;
use App\Models\User;

class ProjectBoardPolicy
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
    public function view(User $user, ProjectBoard $projectBoard): bool
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
    public function update(User $user, ProjectBoard $projectBoard): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $projectBoard->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProjectBoard $projectBoard): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $projectBoard->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProjectBoard $projectBoard): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $projectBoard->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProjectBoard $projectBoard): bool
    {
        return false;
    }
}
