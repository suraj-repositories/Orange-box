<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ModuleAssigned;
use App\Notifications\TaskAssigned;
use App\Notifications\TaskCompleted;
use App\Services\FileService;
use Exception;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //
    public function index(User $user, Request $request)
    {
        $notifications = null;
        $search = "";
        if ($request->has('search')) {
            list($col, $val) = explode('-', $request->search, 2);
            try {
                $notifications = $user->notifications()->where($col, $val)->latest()->paginate(10);
                $search =  $val;
            } catch (Exception $err) {
                $notification = collect();
            }
        } else {
            $notifications = $user->notifications()->latest()->paginate(10);
        }



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
                $linkText = "";

                switch ($notification->type) {
                    case TaskAssigned::class:
                        $linkText = "+1 Task (visit)";
                        break;
                    case TaskCompleted::class:
                        $linkText = "-1 Task (visit)";
                        break;
                    case ModuleAssigned::class:
                        $linkText = "+1 Module (visit)";
                        break;

                    default:
                        $linkText = "Visit";
                        break;
                }

                $notification->link_text = $linkText;
            }
        }
        return view('user.notification.notifications', [
            'notifications' => $notifications,
            'search' => $search
        ]);
    }
}
