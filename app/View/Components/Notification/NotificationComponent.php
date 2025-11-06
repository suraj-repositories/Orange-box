<?php

namespace App\View\Components\Notification;

use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class NotificationComponent extends Component
{
    /**
     * Create a new component instance.
     */

    private $unreadNotificationCount = 0;
    private $unreadNotifications = [];

    public function __construct()
    {
        $user = User::where('id',  Auth::id())->first();
        $this->unreadNotificationCount = $user->unreadNotifications->where('status', 'new')->count();
        $notifications = $user->unreadNotifications()->where('status', 'new')->latest()->take(10)->get();

        $userIds = [];
        foreach ($notifications as $notification) {
            if (!empty($notification->data['from_user'])) {
                $userIds[] = $notification->data['from_user'];
            }
        }
        $users = User::whereIn('id', $userIds)->with('details')->select('avatar', 'id', 'username')->get();

        foreach ($notifications as $notification) {
            if (!empty($notification->data['from_user'])) {
                $notification->from_user = $users->where('id', $notification->data['from_user'])->first();
            }
        }

        $this->unreadNotifications =  $notifications;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {


        return view('components.notification.notification-component', [
            'unreadNotificationCount' => $this->unreadNotificationCount,
            'unreadNotifications' => $this->unreadNotifications
        ]);
    }
}
