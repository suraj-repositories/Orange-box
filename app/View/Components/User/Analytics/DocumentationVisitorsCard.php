<?php

namespace App\View\Components\User\Analytics;

use App\Models\DocumentationPageStat;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Contracts\View\View;

class DocumentationVisitorsCard extends Component
{
    public array $stats = [];
    public array $chart = [];

    public function __construct(
        public ?string $duration = 'month',
        public ?int $documentationId = null,
        public ?int $releaseId = null
    ) {
        [$start, $end] = $this->resolveDateRange($this->duration);

        $baseQuery = DocumentationPageStat::query()
            ->join('documentation_pages as dp', 'dp.id', '=', 'documentation_page_stats.documentation_page_id')
            ->where('dp.user_id', Auth::id())
            ->whereBetween('documentation_page_stats.date', [$start, $end])
            ->when($this->documentationId, fn($q) => $q->where('dp.documentation_id', $this->documentationId))
            ->when($this->releaseId, fn($q) => $q->where('dp.release_id', $this->releaseId));

        $data = (clone $baseQuery)
            ->selectRaw('
                COALESCE(SUM(documentation_page_stats.views), 0) as total_views,
                COALESCE(SUM(documentation_page_stats.unique_visitors), 0) as total_unique,
                COALESCE(SUM(documentation_page_stats.bounces), 0) as total_bounces,
                COALESCE(AVG(documentation_page_stats.avg_time_on_page), 0) as avg_time
            ')
            ->first();

        $views = (int) $data->total_views;
        $bounces = (int) $data->total_bounces;

        $bounceRate = $views > 0
            ? round(($bounces / $views) * 100, 2)
            : 0;

        $this->stats = [
            'views' => number_format($views),
            'unique' => number_format((int) $data->total_unique),
            'bounces' => number_format($bounces),
            'bounce_rate' => $bounceRate . '%',
            'avg_time' => round((float) $data->avg_time) . ' sec',
        ];

        $trend = (clone $baseQuery)
            ->selectRaw('
                DATE(documentation_page_stats.date) as date,
                SUM(documentation_page_stats.unique_visitors) as visitors
            ')
            ->groupByRaw('DATE(documentation_page_stats.date)')
            ->orderByRaw('DATE(documentation_page_stats.date)')
            ->get();

        $dateRange = collect();
        $labelDates = collect();

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $dateRange->push($date->format('Y-m-d'));
            $labelDates->push($date->format('d M'));
        }

        $mapped = $trend->pluck('visitors', 'date');

        $this->chart = [
            'categories' => $labelDates->values()->toArray(),
            'series' => $dateRange->map(fn($d) => (int) ($mapped[$d] ?? 0))->values()->toArray(),
        ];
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
        return view('components.user.analytics.documentation-visitors-card');
    }
}
