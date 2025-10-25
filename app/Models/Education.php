<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Education extends Model
{
    //
    use SoftDeletes;

    protected $table = "educations";

    protected $fillable = [
        'user_id',
        'institution',
        'institution_logo',
        'degree',
        'field_of_study',
        'start_date',
        'end_date',
        'grade',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

     protected $appends = [
        'logo_url'
    ];

    public function getLogoUrlAttribute(){
        if (empty($this->institution_logo) || !Storage::disk('public')->exists($this->institution_logo)) {
            return asset('assets/images/defaults/education-50.svg');
        }
        return Storage::url($this->institution_logo);

    }
}
