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
    public function index(Request $request)
    {
        $hasFilter = collect(['search', 'from_date', 'to_date'])
            ->some(fn($field) => $request->filled($field));

        $users = User::role('user')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('details', function ($details) use ($search) {
                            $details->whereRaw(
                                "CONCAT(first_name, ' ', last_name) LIKE ?",
                                ["%{$search}%"]
                            )
                                ->orWhere('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('contact', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->from_date);
            })
            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->to_date);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();;

        $title = 'Users';
        return view('admin.users.users-list', compact('users', 'title', 'hasFilter'));
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
