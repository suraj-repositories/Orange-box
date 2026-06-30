<?php

namespace App\Jobs;

use App\Models\DocumentationPage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncDocumentationPagesJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        DocumentationPage::query()
            ->where('type', 'file')
            ->whereNotNull('git_link')
            ->chunkById(100, function ($pages) {

                foreach ($pages as $page) {
                    SyncDocumentationPageJob::dispatch($page);
                }

            });
    }
}
