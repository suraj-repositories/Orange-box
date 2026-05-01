<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class FollowController extends Controller
{
    public function follow(User $user, $id)
    {
        try {
            $userToFollow = User::findOrFail($id);

            Auth::user()->follow($userToFollow);

            return response()->json([
                'success' => true,
                'message' => 'Followed successfully!'
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' =>  $th->getMessage()
            ]);
        }
    }

    public function unfollow(User $user, $id)
    {
        try {
            $userToUnfollow = User::findOrFail($id);

            Auth::user()->unfollow($userToUnfollow);
            return response()->json([
                'success' => true,
                'message' => 'Unfollowed successfully!'
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' =>  $th->getMessage()
            ]);
        }
    }
}
