<?php

namespace App\Listeners;

use App\Events\FileDeleted;
use App\Models\SubTask;
use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateRelatedModelsOnFileDelete
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(FileDeleted $event): void
    {
        //
        $file = $event->file;

        if($file->fileable_type == SubTask::class || $file->fileable_type == Task::class){
            $file->fileable()->touch();
        }

    }
}
