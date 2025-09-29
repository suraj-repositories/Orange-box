<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SyntaxStoreController extends Controller
{
    //
    public function create(){
        return view('user.thinkspace.syntax_store.syntax_store_form');
    }
}
