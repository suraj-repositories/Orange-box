<?php

namespace App\Console\Commands;

use App\Models\DocumentationPageStat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AggregateDocumentationStats extends Command
{
    protected $signature = 'stats:aggregate-docs {--date=}';
    protected $description = 'Aggregate documentation page stats daily';

    public function handle(): int
    {
        $date = $this->option('date')
            ? now()->parse($this->option('date'))->toDateString()
            : now()->subDay()->toDateString();

        $start = now()->parse($date)->startOfDay();
        $end   = now()->parse($date)->endOfDay();

        $this->info("Aggregating stats for {$date}...");

        $rows = DB::table('documentation_page_views')
            ->whereBetween('visited_at', [$start, $end])
            ->selectRaw('
                documentation_page_id,
                COUNT(*) as views,
                COUNT(DISTINCT session_id) as unique_visitors,

                -- Bounce: very low engagement or missing duration
                SUM(
                    CASE
                        WHEN duration IS NULL OR duration <= 5
                        THEN 1
                        ELSE 0
                    END
                ) as bounces,

                -- Average time (only valid durations)
                AVG(
                    CASE
                        WHEN duration IS NOT NULL
                        THEN duration
                    END
                ) as avg_time
            ')
            ->groupBy('documentation_page_id')
            ->get();

        if ($rows->isEmpty()) {
            $this->warn("No data found for {$date}");
            return self::SUCCESS;
        }

        $now = now();
        $data = [];

        foreach ($rows as $row) {
            $data[] = [
                'documentation_page_id' => $row->documentation_page_id,
                'date' => $date,
                'views' => (int) $row->views,
                'unique_visitors' => (int) $row->unique_visitors,
                'bounces' => (int) $row->bounces,

                'avg_time_on_page' => round($row->avg_time ?? 0),

                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DocumentationPageStat::upsert(
            $data,
            ['documentation_page_id', 'date'],
            ['views', 'unique_visitors', 'bounces', 'avg_time_on_page', 'updated_at']
        );

        $this->info("Aggregated " . count($data) . " pages for {$date}");

        return self::SUCCESS;
    }
}
