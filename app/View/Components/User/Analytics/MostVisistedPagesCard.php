<?php

namespace App\View\Components\User\Analytics;

use App\Models\DocumentationPageStat;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MostVisistedPagesCard extends Component
{
    public $rows = [];

    public function __construct(
        public ?string $duration = 'month',
        public ?int $releaseId = null
    ) {
        [$start, $end] = $this->resolveDateRange($duration);

        $baseQuery = DocumentationPageStat::query()
            ->join('documentation_pages as dp', 'dp.id', '=', 'documentation_page_stats.documentation_page_id')
            ->where('dp.user_id', Auth::id())
            ->whereBetween('documentation_page_stats.date', [$start, $end]);

        if ($this->releaseId) {
            $baseQuery->where('dp.release_id', $this->releaseId);
        }

        $stats = (clone $baseQuery)
            ->selectRaw('
                documentation_page_id,
                SUM(views) as views,
                SUM(unique_visitors) as unique_visitors,
                SUM(bounces) as bounces
            ')
            ->groupBy('documentation_page_id')
            ->orderByDesc('views')
            ->take(10)
            ->get();

        $dailyStats = (clone $baseQuery)
            ->selectRaw('
                documentation_page_id,
                date,
                SUM(views) as views
            ')
            ->groupBy('documentation_page_id', 'date')
            ->orderBy('date')
            ->get()
            ->groupBy('documentation_page_id');

        $dateRange = collect();
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dateRange->push($date->format('Y-m-d'));
        }

        $labels = $dateRange->map(fn($d) => Carbon::parse($d)->format('d M'))->values()->toArray();

        $viewTrend = $dailyStats->map(function ($rows) use ($dateRange) {

            $mapped = $rows->mapWithKeys(function ($row) {
                return [
                    Carbon::parse($row->date)->format('Y-m-d') => $row->views ?? 0
                ];
            });

            return $dateRange->map(fn($d) => (int) ($mapped[$d] ?? 0))->values()->toArray();
        });

        $this->rows = $stats->map(function ($row, $index) use ($viewTrend, $labels) {

            $bounceRate = $row->views > 0
                ? ($row->bounces / $row->views) * 100
                : 0;

            $route = route('docs.show', [
                'user' => auth()->user(),
                'slug' => $row->page->documentation->url ?? '',
                'version' => $row->page->release?->version ?? "all",
                'path' => $row->page->full_path ?? '',
            ]);

            $path = Str::after($route, 'docs/');

            return [
                'path' => '/' . ($path ?: 'unknown'),
                'url' => $route,
                'views' => number_format($row->views),
                'unique' => number_format($row->unique_visitors),
                'bounce_rate' => number_format($bounceRate, 2) . '%',
                'bounce_trend' => $viewTrend[$row->documentation_page_id] ?? [],
                'labels' => $labels,
                'sparkline_id' => 'sparkline-bounce-' . ($index + 1),
            ];
        });
    }

    private function resolveDateRange(string $duration): array
    {
        return match ($duration) {
            'yesterday' => [
                now()->subDay()->startOfDay(),
                now()->subDay()->endOfDay(),
            ],
            'week' => [
                now()->subDays(6)->startOfDay(),
                now()->endOfDay(),
            ],
            'month' => [
                now()->startOfMonth(),
                now()->endOfDay(),
            ],
            default => [
                now()->startOfMonth(),
                now()->endOfDay(),
            ],
        };
    }

    public function render(): View|Closure|string
    {
        return view('components.user.analytics.most-visisted-pages-card');
    }
}
