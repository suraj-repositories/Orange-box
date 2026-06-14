<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    //
    public function index()
    {
        $users = User::role('user')
            ->latest()
            ->paginate(10);

        $title = 'Users';
        return view('admin.users.users-list', compact('users', 'title'));
    }

    public function updateStatus(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'status' => $user->is_active
            ]);
        }

        $user->update([
            'is_active' => $request->is_active
        ]);

        return response()->json([
            'success' => true,
            'status' => $user->is_active
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('user')) {
            $user->delete();
        }

        return redirect()->back()->with('success', 'User deleted successfully!');
    }

    public function loginUser(User $user)
    {
        session([
            'impersonator_admin_id' => auth()->id(),
        ]);

        Auth::loginUsingId($user->id);

        return redirect()->to(authRoute('user.dashboard'));
    }
}
