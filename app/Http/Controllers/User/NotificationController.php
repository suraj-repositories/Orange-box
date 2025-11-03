<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //
    public function index(User $user)
    {

        return view('user.notification.notifications', [
            'notifications' => $user->notifications()->paginate(10)
        ]);
    }
}
