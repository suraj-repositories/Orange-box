<?php
namespace App\Jobs;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteCommentableCommentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $chunkSize = 1000;
    protected string $commentableType;
    protected int $commentableId;

    public function __construct(string $commentableType, int $commentableId)
    {
        $this->commentableType = $commentableType;
        $this->commentableId = $commentableId;
    }

    public function handle(): void
    {
        $commentable = $this->commentableType::withTrashed()->find($this->commentableId);

        if (!$commentable) {
            return;
        }

        $query = Comment::where('commentable_type', $this->commentableType)
            ->where('commentable_id', $this->commentableId)
            ->whereNull('parent_id')
            ->orderBy('id')
            ->cursor();

        $chunk = [];
        foreach ($query as $comment) {
            $chunk[] = $comment->id;

            if (count($chunk) >= $this->chunkSize) {
                $this->dispatchChunk($chunk);
                $chunk = [];
                gc_collect_cycles();
            }
        }

        if (!empty($chunk)) {
            $this->dispatchChunk($chunk);
        }

        unset($query, $chunk, $commentable);
        gc_collect_cycles();
    }

    protected function dispatchChunk(array $commentIds): void
    {
        foreach ($commentIds as $commentId) {
            DeleteCommentTreeJob::dispatch($commentId);
        }
    }
}
