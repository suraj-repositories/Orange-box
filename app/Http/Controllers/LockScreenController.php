<?php

namespace App\Http\Controllers;

use App\Models\ScreenLock;
use App\Models\UserKey;
use App\Models\UserScreenLockPin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LockScreenController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();
        if ($user->isLocked()) {
            return view('auth.lock-screen');
        } else {
            $screenLock = ScreenLock::where('user_id', $user->id)
                ->latest()
                ->first();
            if ($screenLock) {
                return redirect()->to($screenLock->redirect_url);
            }
            return redirect()->route('login');
        }
    }

    public function unlock(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'pin' => 'required|max:8|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }
        $user = Auth::user();
        $screenLockPin = UserScreenLockPin::where('user_id', $user->id)->where('status', 'active')->first();

        if ($screenLockPin->verifyPin($request->pin)) {

            $redirectUrl = route('login');
            $screenLock = ScreenLock::where('user_id', $user->id)
                ->latest()
                ->first();

            if ($screenLock) {
                $screenLock->unlocked = true;
                $screenLock->save();
                $redirectUrl = $screenLock->redirect_url;
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Pin is Valid, Redirecting...',
                'redirect_url' => $redirectUrl
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Wrong PIN!',
        ]);
    }

    public function lock(Request $request)
    {
        $user = Auth::user();
        $unlockPin = $user->screenLockPin()->where('status', 'active')->exists();

        if (!$user->isLocked() && $unlockPin) {
            ScreenLock::create([
                'user_id' => $user->id,
                'redirect_url' => $request->headers->get('referer'),
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'locked_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'redirect_url' =>  route('lock-screen.index')
            ]);
        }

        return response()->json([
            'status' =>  'error',
            'message' => (empty($unlockKey) || empty($unlockKey->screen_lock_pin)) ? 'Unlock PIN not set!' : 'Already Locked!'
        ]);
    }
}
