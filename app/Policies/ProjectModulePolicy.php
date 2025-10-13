<?php

namespace App\Policies;

use App\Models\ProjectModule;
use App\Models\User;

class ProjectModulePolicy
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
    public function view(User $user, ProjectModule $projectModule): bool
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
    public function update(User $user, ProjectModule $projectModule): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $projectModule->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProjectModule $projectModule): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $projectModule->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProjectModule $projectModule): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $projectModule->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProjectModule $projectModule): bool
    {
        return false;
    }
}
