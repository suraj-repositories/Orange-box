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
        $notifications = $user->notifications()->paginate(10);

        $userIds = [];
        foreach ($notifications as $notification) {
            if (!empty($notification->data['from_user'])) {
                $userIds[] = $notification->data['from_user'];
            }
        }

        $users = User::whereIn('id', $userIds)->with('details')->select('avatar', 'id', 'username')->get();

        foreach ($notifications->getCollection() as $notification) {
            if (!empty($notification->data['from_user'])) {
                $notification->from_user = $users->where('id', $notification->data['from_user'])->first();
            }
        }
        return view('user.notification.notifications', [
            'notifications' => $notifications
        ]);
    }
}
