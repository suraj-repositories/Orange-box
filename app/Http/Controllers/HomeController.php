<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    public function index(Request $request)
    {
        if(!Auth::check()){
            return redirect()->route('login')->with('error', 'Please login first!');
        }

        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard')->with('success', 'Login Successful!');
        } else if ($user->hasRole('editor', Auth::user()->id)) {
            return redirect()->route('editor.dashboard')->with('success', 'Login Successful!');
        } else if ($user->hasRole('user')) {
            return redirect()->to(authRoute('user.dashboard'))->with('success', 'Login Successful!');
        }else{
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('login')->with('error', 'Something went wrong!');


    }
}
