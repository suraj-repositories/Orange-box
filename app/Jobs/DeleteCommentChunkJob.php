<?php

namespace App\Jobs;

use App\Models\Comment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class DeleteCommentChunkJob implements ShouldQueue
{
    use Queueable;

    protected $commentIds;
    /**
     * Create a new job instance.
     */
    public function __construct(array $commentIds)
    {
        $this->commentIds = $commentIds;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Comment::whereIn('id', $this->commentIds)
            ->update(['deleted_at' => now()]);
    }
}
