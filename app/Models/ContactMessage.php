<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_READ = 'read';
    public const STATUS_REPLIED = 'replied';
    public const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'name',
        'email',
        'subject',
        'category',
        'message',
        'attachment',
        'status',
    ];
    //
}
