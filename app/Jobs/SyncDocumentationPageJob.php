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
        public int $pageId
    ) {}

    public function handle(GitService $gitService): void
    {

        if ($this->batch()?->cancelled()) {
            return;
        }
        $page = DocumentationPage::find($this->pageId);

        if (! $page) {
            return;
        }

        try {

            $markdown = $gitService->loadGitPageContent($page->git_link);

            $page->update([
                'content' => $markdown,
                'content_format' => 'markdown',
            ]);

            $gitService->generateSections($page);
        } catch (\Throwable $e) {

            Log::error('Failed syncing page', [
                'page_id' => $page->id,
                'url' => $page->git_link,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
