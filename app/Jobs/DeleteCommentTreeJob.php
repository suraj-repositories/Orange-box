<?php

namespace App\Jobs;

use App\Models\Comment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteCommentTreeJob implements ShouldQueue
{
    use Queueable;

    protected $chunkSize = 1000;
    protected $commentId;
    /**
     * Create a new job instance.
     */
    public function __construct(int $commentId) {
        $this->commentId = $commentId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $comment = Comment::withTrashed()->findOrFail($this->commentId);
        $page = 1;

        do {
            $ids = $comment->getDescendantIdsChunked($this->chunkSize, $page);

            if (empty($ids)) break;

            DeleteCommentChunkJob::dispatch($ids);

            $page++;
        } while (count($ids) === 1000);

        $comment->delete();

    }
}
