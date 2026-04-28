<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DailyDigest;
use App\Models\DocumentationPageStat;
use App\Models\DocumentationPageView;
use App\Models\PageView;
use App\Models\SyntaxStore;
use App\Models\ThinkPad;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //

    public function index(User $user)
    {
        $title = "Dashboard";

        return view('user.dashboard.dashboard', [
            'title' => $title
        ]);
    }

    public function analyticalDashboard(User $user)
    {
        $stats = DB::table('users')
            ->selectRaw("
            (SELECT COUNT(*) FROM daily_digests WHERE user_id = ?) AS digestionCount,
            (SELECT COUNT(*) FROM think_pads WHERE user_id = ?) AS thinkPadCount,
            (SELECT COUNT(*) FROM syntax_stores WHERE user_id = ?) AS syntaxCount,
            (SELECT COUNT(*) FROM documentations WHERE user_id = ?) AS documentationCount
        ", [$user->id, $user->id, $user->id, $user->id])
            ->first();

        $now = Carbon::now();

        $thisMonthStart = $now->copy()->startOfMonth();
        $thisMonthEnd   = $now->copy()->endOfMonth();

        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd   = $now->copy()->subMonth()->endOfMonth();

        $digestStats = $this->getGrowthStats(
            DailyDigest::class,
            'daily_digests',
            $user,
            $thisMonthStart,
            $thisMonthEnd,
            $lastMonthStart,
            $lastMonthEnd
        );

        $stats->digestionGrowth = $digestStats['growth'];
        $stats->digestionGrowthPositive = $digestStats['positive'];

        $thinkStats = $this->getGrowthStats(
            ThinkPad::class,
            'think_pads',
            $user,
            $thisMonthStart,
            $thisMonthEnd,
            $lastMonthStart,
            $lastMonthEnd
        );

        $stats->thinkPadGrowth = $thinkStats['growth'];
        $stats->thinkPadGrowthPositive = $thinkStats['positive'];

        $syntaxStats = $this->getGrowthStats(
            SyntaxStore::class,
            'syntax_stores',
            $user,
            $thisMonthStart,
            $thisMonthEnd,
            $lastMonthStart,
            $lastMonthEnd
        );

        $stats->syntaxGrowth = $syntaxStats['growth'];
        $stats->syntaxGrowthPositive = $syntaxStats['positive'];

        $thisMonthUnique = DocumentationPageStat::whereBetween('date', [$thisMonthStart, $thisMonthEnd])
            ->sum('unique_visitors');

        $lastMonthUnique = DocumentationPageStat::whereBetween('date', [$lastMonthStart, $lastMonthEnd])
            ->sum('unique_visitors');

        $growth = $lastMonthUnique > 0
            ? (($thisMonthUnique - $lastMonthUnique) / $lastMonthUnique) * 100
            : ($thisMonthUnique > 0 ? 100 : 0);

        $stats->uniqueVisitorGrowth = round($growth, 1);
        $stats->uniqueVisitorPositive = $growth >= 0;

        return view('user.dashboard.analytics-dashboard', compact('stats'));
    }

    private function getGrowthStats($model, $table, $user, $thisMonthStart, $thisMonthEnd, $lastMonthStart, $lastMonthEnd)
    {
        $baseQuery = PageView::where('viewable_type', $model)
            ->join($table, "$table.id", '=', 'page_views.viewable_id')
            ->where("$table.user_id", $user->id);

        $thisMonth = (clone $baseQuery)
            ->whereBetween('page_views.visited_at', [$thisMonthStart, $thisMonthEnd])
            ->count();

        $lastMonth = (clone $baseQuery)
            ->whereBetween('page_views.visited_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $growth = $lastMonth > 0
            ? (($thisMonth - $lastMonth) / $lastMonth) * 100
            : ($thisMonth > 0 ? 100 : 0);

        return [
            'growth' => round($growth, 1),
            'positive' => $growth >= 0,
        ];
    }
}
