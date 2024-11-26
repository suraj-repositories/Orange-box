<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::attempt($request->only(['email', 'password']))) {
            if (Auth::user()->role_id == 1) {
                return redirect()->route('admin.dashboard')->with('success', 'Login Successful!');
            } elseif (Auth::user()->role_id == 2) {
                return redirect()->route('user.dashboard')->with('success', 'Login Successful!');
            } else {
                return redirect()->route('login')->with('error', 'Not a valid role');
            }
        }

        return redirect()->route('login')->with('error', 'Wrong Credentials!');
    }

    public function showRegistrationForm(){
        return view("auth.register");
    }
}
