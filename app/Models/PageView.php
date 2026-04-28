<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PageView extends Model
{
      protected $fillable = [
        'viewable_type',
        'viewable_id',
        'session_id',
        'ip',
        'user_agent',
        'duration',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'duration' => 'integer',
    ];

    /**
     * Polymorphic relation
     */
    public function viewable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope: recent views
     */
    public function scopeRecent($query)
    {
        return $query->orderByDesc('visited_at');
    }

    /**
     * Scope: unique session views
     */
    public function scopeUniqueSession($query)
    {
        return $query->groupBy('session_id');
    }

    /**
     * Scope: for a specific model
     */
    public function scopeForModel($query, string $type, int $id)
    {
        return $query->where('viewable_type', $type)
                     ->where('viewable_id', $id);
    }
}
