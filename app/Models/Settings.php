<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Settings extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'title',
        'key',
        'value',
        'settings_category_id',
        'description'
    ];

    public function category(){
        return $this->belongsTo(SettingsCategory::class);
    }
}
