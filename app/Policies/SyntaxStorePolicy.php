<?php

namespace App\Policies;

use App\Models\SyntaxStore;
use App\Models\User;

class SyntaxStorePolicy
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
    public function view(User $user, SyntaxStore $syntaxStore): bool
    {
        return true;
        //  if($syntaxStore->visibility == 'public'){
        //     return true;
        // }

        // return $user->id == $syntaxStore->user_id ? true : false;
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
    public function update(User $user, SyntaxStore $syntaxStore): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $syntaxStore->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SyntaxStore $syntaxStore): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $syntaxStore->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SyntaxStore $syntaxStore): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $syntaxStore->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SyntaxStore $syntaxStore): bool
    {
        return false;
    }
}
