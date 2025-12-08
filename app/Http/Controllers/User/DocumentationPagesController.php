<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\User;
use Illuminate\Http\Request;

class DocumentationPagesController extends Controller
{
    //
    public function index(User $user, Documentation $documentation){
        return view('user.documentation.documentation_pages', [
            'documentation' => $documentation
        ]);
    }
}
