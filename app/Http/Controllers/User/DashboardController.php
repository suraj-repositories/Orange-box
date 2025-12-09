<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DailyDigest;
use App\Models\SyntaxStore;
use App\Models\ThinkPad;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //

    public function index(User $user)
    {

        $counts = DB::table('users')
            ->selectRaw("
                    (SELECT COUNT(*) FROM daily_digests WHERE user_id = ?) AS digestionCount,
                    (SELECT COUNT(*) FROM think_pads WHERE user_id = ?) AS thinkPadCount,
                    (SELECT COUNT(*) FROM syntax_stores WHERE user_id = ?) AS syntaxCount,
                    (SELECT COUNT(*) FROM documentations WHERE user_id = ?) AS documentationCount
                ", [$user->id, $user->id, $user->id, $user->id])
            ->first();

        return view('user.dashboard', [
            'counts' => $counts
        ]);
    }
}
