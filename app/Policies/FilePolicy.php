<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FilePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, File $file)
    {
         if($user->hasRole('admin')){
            return true;
        }
        return $file->user_id === $user->id;
    }


    public function delete(User $user, File $file)
    {
        if($user->hasRole('admin')){
            return true;
        }

        if ($file->fileable instanceof \App\Models\DailyDigest) {
            return $file->fileable->user_id === $user->id;
        }

         return $file->user_id === $user->id;
    }

      public function update(User $user, File $file): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        return $file->user_id === $user->id;
    }
}
