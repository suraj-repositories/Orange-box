<?php

namespace App\Jobs;

use App\Models\DocumentationPage;
use App\Services\GitService;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncDocumentationPageJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    public $tries = 3;

    public function __construct(
        public DocumentationPage $page
    ) {}

    public function handle(GitService $gitService): void
    {
        try {

            $markdown = $gitService->loadGitPageContent($this->page->git_link);

            $this->page->update([
                'content' => $markdown,
                'content_format' => 'markdown',
            ]);

        } catch (\Throwable $e) {

            Log::error('Failed syncing page', [
                'page_id' => $this->page->id,
                'url' => $this->page->git_link,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
