<?php

namespace App\Http\Controllers\Common;

use App\Facades\Setting;
use App\Http\Controllers\Controller;
use App\Models\ScreenLock;
use App\Models\Settings;
use App\Models\User;
use App\Models\UserKey;
use App\Models\UserMasterKey;
use App\Models\UserScreenLockPin;
use App\Models\UserSetting;
use App\Services\SuggestionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function __construct(public SuggestionService $suggestionService) {}

    //
    public function changeUsername(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:30'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('username')
            ], 422);
        }

        $username = $request->username;

        DB::beginTransaction();

        try {
            $isExists = User::where('username', $username)->exists();

            if ($isExists) {

                $suggestions = $this->suggestionService->suggestUsername($username);

                return response()->json([
                    'success' => false,
                    'suggestions' => $suggestions,
                    'message' => 'Username "' . $username . '" is not available. Please choose another.'
                ], 200);
            }

            User::where('id', Auth::id())->update([
                'username' => $username
            ]);

            $user = User::find(Auth::id());

            Auth::login($user, true);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Username changed successfully!',
                'username' => $username,
                'redirect_url' => authRoute('user.settings.index')
            ], 200);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again.'
            ], 500);
        }
    }
    public function changeEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:30'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('username')
            ], 422);
        }

        $username = $request->username;

        DB::beginTransaction();

        try {
            $isExists = User::where('username', $username)->exists();

            if ($isExists) {

                $suggestions = $this->suggestionService->suggestUsername($username);

                return response()->json([
                    'success' => false,
                    'suggestions' => $suggestions,
                    'message' => 'Username "' . $username . '" is not available. Please choose another.'
                ], 200);
            }

            User::where('id', Auth::id())->update([
                'username' => $username
            ]);

            $user = User::find(Auth::id());

            Auth::login($user, true);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Username changed successfully!',
                'username' => $username,
                'redirect_url' => authRoute('user.settings.index')
            ], 200);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Please try again.'
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' =>  'required',
            'password' =>  'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = User::where('id', Auth::id())->first();

        if (Hash::check($request->current_password, $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Wrong current password!'
        ]);
    }

    public function setLockScreen(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pin' => 'required|min:3',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('username')
            ], 422);
        }

        $user = Auth::user();
        try {

            if (Hash::check($request->password, $user->password)) {

                $encryptedPin = Hash::make($request->pin);
                UserScreenLockPin::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'pin' => $encryptedPin,
                        'status' => 'active',
                    ]
                );

                $lockScreenEnabledGlobally = Settings::where('key', 'lock_screen_enabled')->where('is_enabled', true)->first();

                if (!empty($lockScreenEnabledGlobally)) {
                    UserSetting::create([
                        'user_id' => $user->id,
                        'setting_id' => $lockScreenEnabledGlobally->id,
                        'value' => true
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'New unlock pin set successfully!'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Lock screen feature is blocked!'
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Wrong password!'
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => $ex->getMessage()
            ]);
        }
    }
}
