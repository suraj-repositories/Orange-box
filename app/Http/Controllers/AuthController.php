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

            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard')->with('success', 'Login Successful!');
            } else if ($user->hasRole('editor', Auth::user()->id)) {
                return redirect()->route('editor.dashboard')->with('success', 'Login Successful!');
            } else if ($user->hasRole('user')) {
                return redirect()->to(authRoute('user.dashboard'))->with('success', 'Login Successful!');
            }

            return redirect()->route('home')->with('success', 'Login Successful!');
        }

        return redirect()->route('login')->with('error', 'Wrong Credentials!');
    }

    public function showRegistrationForm()
    {
        return view("auth.register");
    }

    public function register(Request $request)
    {
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

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('success', 'Logout Successfully!');
        }

        return redirect()->route('login')->with('error', 'Access denied!');
    }
}
