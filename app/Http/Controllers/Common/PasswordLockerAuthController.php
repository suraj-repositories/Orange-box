<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Mail\PasswordLockerOtpMail;
use App\Models\MasterKey;
use App\Models\Otp;
use App\Models\PasswordLocker;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordLockerAuthController extends Controller
{
    //

    public function sendEmailOtp()
    {

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access!'
            ], 401);
        }

        if (empty($user->email)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email not set!'
            ], 401);
        }

        try {
            $otpObj = Otp::generate($user->id);
            Mail::to('suraj2002fake@gmail.com')->send(new PasswordLockerOtpMail($otpObj->otp));

            return response()->json([
                'status' => 'success',
                'message' => 'Otp Sent successfully!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyEmailOtp(Request $request)
    {

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access!'
            ], 401);
        }

        $valid = Otp::verify($user->id, $request->otp);

        if (!$valid) {
            return response()->json([
                'status' => 'error',
                'message' => "Invalid Otp!"
            ], 401);
        }
        $encryptedPassword = PasswordLocker::where('user_id', $user->id)->where('id', $request->password_locker_id)->value('password');

        if (!empty($encryptedPassword)) {

            PasswordLocker::where('id', $request->password_locker_id)->update([
                'last_used_at' => now()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Ok!',
                'key' => Crypt::decryptString($encryptedPassword)
            ]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Unautorized access!'], 401);
        }
    }

    public function verfiyMasterKey(Request $request)
    {
        $mk = $request->input('master_key');
        $user = Auth::user();
        $masterKey = MasterKey::where('user_id', $user->id)->orderByDesc('id')->first();
        if(!$masterKey){
            return response()->json([
                'status' => 'error',
                'message' => "Master key not set!"
            ], 401);
        }

        if (Hash::check($mk, $masterKey->password)) {
            $encryptedPassword = PasswordLocker::where('user_id', $user->id)->where('id', $request->password_locker_id)->value('password');

            if (!empty($encryptedPassword)) {

                PasswordLocker::where('id', $request->password_locker_id)->update([
                    'last_used_at' => now()
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Ok!',
                    'key' => Crypt::decryptString($encryptedPassword)
                ]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Unautorized access!'], 401);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => "Invalid Otp!"
            ], 401);
        }
    }
}
