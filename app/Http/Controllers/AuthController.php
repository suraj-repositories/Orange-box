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

            $role = strtolower(Auth::user()->role->name);

            if($role == 'admin'){
                return redirect()->route('admin.dashboard')->with('success', 'Login Successful!');
            }else if($role == 'editor'){
                return redirect()->route('editor.dashboard', Auth::user()->id)->with('success', 'Login Successful!');

            }else if($role == 'user'){
                return redirect()->route('user.dashboard', Auth::user()->id)->with('success', 'Login Successful!');

            }
        }

        return redirect()->route('login')->with('error', 'Wrong Credentials!');
    }

    public function showRegistrationForm(){
        return view("auth.register");
    }

    public function register(Request $request){
        $validated = $request->validate([
            'first_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => Role::where('name', 'User')->value('id'),
        ]);

        $user_detail = UserDetails::create([
            'first_name' => $validated['first_name'],
            'last_name' => $request->last_name,
            'user_id' => $user->id,
        ]);

        Auth::login($user);
        $role = strtolower(Auth::user()->role->name);

        if($role == 'admin'){
            return redirect()->route('admin.dashboard')->with('success', 'Login Successful!');
        }else if($role == 'editor'){
            return redirect()->route('editor.dashboard', Auth::user()->id)->with('success', 'Login Successful!');

        }else if($role == 'user'){
            return redirect()->route('user.dashboard', Auth::user()->id)->with('success', 'Login Successful!');

        }

        return redirect()->route('login')->with('error', 'Login Failed!');

    }

}
