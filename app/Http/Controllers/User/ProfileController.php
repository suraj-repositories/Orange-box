<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PasswordLocker;
use App\Models\User;
use App\Models\WorkExperience;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    public function index(User $user){


        return view('user.account.profile.user_profile', [
            'user' => $user,
            'passwords' => PasswordLocker::where('user_id', $user->id)->paginate(),
            'experiences' => WorkExperience::where('user_id', $user->id)->orderByDesc('id')->get()
        ]);
    }
}
