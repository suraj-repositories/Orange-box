<?php

namespace App\Traits;
use Illuminate\Support\Str;

trait HasUuid
{
    //
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}
