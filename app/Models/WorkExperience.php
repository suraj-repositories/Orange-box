<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class WorkExperience extends Model
{
    use SoftDeletes;
    //
    protected $fillable = [
        'job_title',
        'company',
        'company_logo',
        'location',
        'start_date',
        'end_date',
        'currently_working',
        'employment_type',
        'description',
        'user_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'updated_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    protected $appends = [
        'duration',
        'logo_url'
    ];

    public function getDurationAttribute()
    {
        $start = $this->start_date;
        $end = $this->end_date;

        $years = $start->diffInYears($end);
        $months = $start->addYears($years)->diffInMonths($end);

        $years = round($years);
        $months = round($months);
        $yearText = $years > 1 ? "$years Years " : ($years === 1 ? "$years Year " : '');
        $monthText = $months > 1 ? "$months Months" : ($months === 1 ? "$months Month" : '');

        return trim($yearText . $monthText);
    }

    public function getLogoUrlAttribute(){
        if (empty($this->company_logo) || !Storage::disk('public')->exists($this->company_logo)) {
            return asset('assets/images/defaults/experience-company-50.svg');
        }
        return Storage::url($this->company_logo);

    }

}
