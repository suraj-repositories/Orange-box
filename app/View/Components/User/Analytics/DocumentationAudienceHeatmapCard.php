<?php

namespace App\View\Components\User\Analytics;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class DocumentationAudienceHeatmapCard extends Component
{
    public array $heatmap = [];

    /**
     * Create a new component instance.
     */

    public function __construct(
        public ?string $duration = 'month',
        public ?int $documentationId = null,
        public ?int $releaseId = null
    ) {
        //
        $views = DB::table('documentation_page_views as v')
            ->join('documentation_pages as dp', 'dp.id', '=', 'v.documentation_page_id')
            ->where('dp.user_id', Auth::id())
            ->when($this->documentationId, fn($q) => $q->where('dp.documentation_id', $this->documentationId))
            ->when($this->releaseId, fn($q) => $q->where('dp.release_id', $this->releaseId))
            ->selectRaw('
                DAYOFWEEK(v.visited_at) as day,
                HOUR(v.visited_at) as hour,
                COUNT(*) as total
            ')
            ->groupBy('day', 'hour')
            ->get();

        $daysMap = [
            2 => 'Mon',
            3 => 'Tue',
            4 => 'Wed',
            5 => 'Thu',
            6 => 'Fri',
            7 => 'Sat',
            1 => 'Sun'
        ];

        $timeSlots = ['12 AM', '3 AM', '6 AM', '9 AM', '12 PM', '3 PM', '6 PM', '9 PM'];

        $bucket = function ($hour) {
            return match (true) {
                $hour < 3 => 0,
                $hour < 6 => 1,
                $hour < 9 => 2,
                $hour < 12 => 3,
                $hour < 15 => 4,
                $hour < 18 => 5,
                $hour < 21 => 6,
                default => 7,
            };
        };

        $data = [];

        foreach ($daysMap as $dayNum => $dayName) {
            $row = array_fill(0, 8, 0);

            foreach ($views->where('day', $dayNum) as $item) {
                $index = $bucket($item->hour);
                $row[$index] += $item->total;
            }

            $data[] = [
                'name' => $dayName,
                'data' => collect($timeSlots)->map(fn($slot, $i) => [
                    'x' => $slot,
                    'y' => $row[$i]
                ])->toArray()
            ];
        }

        $this->heatmap = $data;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user.analytics.documentation-audience-heatmap-card');
    }
}
