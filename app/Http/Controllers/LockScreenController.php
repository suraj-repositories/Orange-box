<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LockScreenController extends Controller
{
    //
    public function index()
    {
        return view('auth.lock-screen');
    }

    public function unlock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pin' => 'required|max:6|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }
        $user = Auth::user();

        $redirectUrl = "/";
        if ($user->hasRole('admin')) {
            $redirectUrl = route('admin.dashboard');
        } else if ($user->hasRole('editor', Auth::user()->id)) {
            $redirectUrl = route('editor.dashboard');
        } else if ($user->hasRole('user')) {
             $redirectUrl =  authRoute('user.dashboard');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Pin is Valid, Redirecting...',
            'redirect_url' => $redirectUrl
        ]);
    }
}
