<?php

namespace App\Models;

use App\Traits\Likeable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Comment extends Model
{
    use SoftDeletes, Likeable;

    protected $fillable = [
        'user_id',
        'parent_id',
        'root_id',
        'commentable_type',
        'commentable_id',
        'message',
    ];


    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function totalReplies()
    {
        return $this->replies()->count();
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function topLevelReplies()
    {
        return $this->hasMany(Comment::class, 'root_id')
            ->whereNotNull('parent_id')
            ->where('root_id', $this->id);
    }
    public function totalTopLevelReplies()
    {
        return $this->topLevelReplies()->count();
    }

    public function getDescendantIdsChunked(int $chunkSize = 1000, int $page = 1): array
    {
        $startId = $this->id;
        $offset = ($page - 1) * $chunkSize;

        $query = <<<SQL
        WITH RECURSIVE descendants AS (
            SELECT id, parent_id
            FROM comments
            WHERE id = ?

            UNION ALL

            SELECT c.id, c.parent_id
            FROM comments c
            INNER JOIN descendants d ON c.parent_id = d.id
        )
        SELECT id FROM descendants
        WHERE id != ?
        ORDER BY id
        LIMIT ? OFFSET ?
    SQL;

        $results = DB::select($query, [$startId, $startId, $chunkSize, $offset]);

        return array_map(fn($row) => $row->id, $results);
    }
}
