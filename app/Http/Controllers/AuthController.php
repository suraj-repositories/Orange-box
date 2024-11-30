<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            $user = Auth::user();
            return redirect()->route('home')->with('success', 'Login Successful!');
        }

        return redirect()->route('login')->with('error', 'Wrong Credentials!');
    }

    public function showRegistrationForm(){
        return view("auth.register");
    }

    public function register(Request $request){
        $validated = $request->validate([
            'username' => 'required|unique:users,email',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole('user');
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registration Successful!');

    }

}
