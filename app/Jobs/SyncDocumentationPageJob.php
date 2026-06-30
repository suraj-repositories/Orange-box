<?php

namespace App\Jobs;

use App\Models\DocumentationPage;
use App\Services\GitService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SyncDocumentationPageJob implements ShouldQueue
{
    use Queueable;

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
