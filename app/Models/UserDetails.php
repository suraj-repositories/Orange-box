<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDetails extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'tag_line',
        'contact',
        'public_email',
        'bio',
        'user_id',
        'deleted_at'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
