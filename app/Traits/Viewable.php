<?php

namespace App\Traits;

use App\Models\PageView;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Viewable
{
    /**
     * Polymorphic views relation
     */
    public function views(): MorphMany
    {
        return $this->morphMany(PageView::class, 'viewable');
    }

    /**
     * Track a view safely (prevents duplication per session)
     */
    public function recordView(array $data = []): void
    {
        $sessionId = $data['session_id'] ?? session()->getId();

        $alreadyViewed = $this->views()
            ->where('session_id', $sessionId)
            ->exists();

        if ($alreadyViewed) {
            return;
        }

        $this->views()->create([
            'session_id' => $sessionId,
            'ip' => $data['ip'] ?? request()->ip(),
            'user_agent' => $data['user_agent'] ?? request()->userAgent(),
            'visited_at' => now(),
        ]);
    }
}
