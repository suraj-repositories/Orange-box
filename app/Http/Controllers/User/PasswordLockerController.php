<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PasswordLocker;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class PasswordLockerController extends Controller
{
    //
    public function index(User $user)
    {
        $passwords = PasswordLocker::where('user_id', $user->id)
            ->where(function ($query) {
                $query->where('expires_at', '>=', now())
                    ->orWhereNull('expires_at');
            })
            ->paginate(10);
        $passwords->getCollection()->transform(function ($password) {
            $password->notes = $password->notes ? Crypt::decryptString($password->notes) : null;
            return $password;
        });

        return view('user.account.password_locker.password_locker_list', [
            'title' => 'Password Locker',
            'passwords' => $passwords
        ]);
    }

    public function store(User $user, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'url' => 'nullable|url',
            'expires_at' => 'nullable|date|after_or_equal:today',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'field' => $validator->errors()->keys()[0],
                'message' => $validator->errors()->first()
            ], 422);
        }


          PasswordLocker::create([
            'user_id' => $user->id,
            'username' => $request->username,
            'password' => Crypt::encryptString($request->password),
            'url' => $request->url,
            'expires_at' => $request->expires_at,
            'notes' => !empty($request->notes) ? Crypt::encryptString($request->notes) : null
        ]);


        return response()->json([
            'status' => 'success',
            'message' => 'Password successfully saved in locker!',
        ]);
    }

    public function update(User $user, PasswordLocker $passwordLocker, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'nullable',
            'url' => 'nullable|url',
            'expires_at' => 'nullable|date|after_or_equal:today',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'field' => $validator->errors()->keys()[0],
                'message' => $validator->errors()->first()
            ], 422);
        }
        $passwordLocker->username = $request->username;
        $passwordLocker->password = !empty($request->password)
            ? Crypt::encryptString($request->password)
            : $passwordLocker->password;

        $passwordLocker->url = $request->url;
        $passwordLocker->expires_at = $request->expires_at;

        $passwordLocker->notes = !empty($request->notes) ? Crypt::encryptString($request->notes) : null;
        $passwordLocker->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Password successfully updated and saved in locker!',
        ]);
    }

    public function destroy(User $user, PasswordLocker $passwordLocker, Request $request)
    {
        Gate::authorize('delete', $passwordLocker);
        $passwordLocker->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Password deleted successfully!',
        ]);
    }

    public function showPassword(User $user, PasswordLocker $passwordLocker, Request $request){
        Gate::authorize('view', $passwordLocker);

        return response()->json([
            'status' => 'success',
            'key' => Crypt::decryptString($passwordLocker->password)
        ]);

    }
}
