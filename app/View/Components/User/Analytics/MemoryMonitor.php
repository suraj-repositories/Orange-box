<?php

namespace App\View\Components\User\Analytics;

use App\Models\File;
use App\Services\FileService;
use App\Services\Impl\FileServiceImpl;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class MemoryMonitor extends Component
{
    /**
     * Create a new component instance.
     */
    public $usedMemoryPercent = 0;
    public $usedMemory = 0;
    public $availableMemory = 0;

    public function __construct(FileService $fileService = new FileServiceImpl())
    {
        //
        $usedMemoryInBytes = File::where('user_id', Auth::id())->sum('file_size') ?? 0;

        $availableMemoryInBytes = config('app.per_user_storage') - $usedMemoryInBytes;
        $availableMemoryInBytes = $availableMemoryInBytes >= 0 ? $availableMemoryInBytes : 0;

        $this->usedMemoryPercent = round($usedMemoryInBytes / config('app.per_user_storage') * 100, 1);
        $this->usedMemory = $fileService->getFormattedSize($usedMemoryInBytes, 2);
        $this->availableMemory = $fileService->getFormattedSize($availableMemoryInBytes, 2);

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user.analytics.memory-monitor');
    }
}
