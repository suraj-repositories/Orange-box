<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    //
    public function markAsRead(DatabaseNotification $notification, Request $request)
    {

        $user = Auth::user();

        if (!($user->id == $notification->notifiable_id && $notification->notifiable_type == User::class)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Permission denied!'
            ]);
        }

        if (!empty($notification->read_at)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Already readed!'
            ]);
        }

        if ($notification->status == 'new') {
            $notification->status = 'cleared';
        }

        $notification->read_at = now();
        $notification->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Readed successfully!'
        ]);
    }

    public function clearNotifications(Request $request)
    {

        $user = User::find(Auth::id());

        $user->notifications()->where('status', 'new')->update([
            'status' => 'cleared'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Cleared successfully!',
            'emptyComponent' => view('components.no-data')->render()
        ]);
    }

    public function deleteAllNotifications(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notifications' => 'array|required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'field' => $validator->errors()->keys()[0],
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = Auth::user();

        $notificationCount = DatabaseNotification::whereIn('id', $request->notifications)
            ->where('notifiable_type', User::class)
            ->where('notifiable_id', $user->id)
            ->count();

        DatabaseNotification::whereIn('id', $request->notifications)
            ->where('notifiable_type', User::class)
            ->where('notifiable_id', $user->id)
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Deleted successfully!',
            'deleted_count' => $notificationCount
        ]);
    }
}
