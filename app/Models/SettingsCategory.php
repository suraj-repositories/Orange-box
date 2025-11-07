<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SettingsCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description'
    ];

    public function settings(){
        return $this->hasMany(Settings::class);
    }

    public function getIconUrlAttribute(){
        return asset($this->icon);
    }

}
