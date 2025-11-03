<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function searchUsers(Request $request)
    {
        if (!$request->has('username') || !Auth::check()) {
            abort(403, "Username is required!");
        }

        $users = User::where('username', 'like', "%$request->username%")->select('id', 'username', 'avatar')->take(5)->get();

        foreach ($users as $user) {
            $user->avatar = $user->profilePicture();
        }

        return response()->json([
            'status' => 200,
            'data' => $users
        ]);
    }
}
