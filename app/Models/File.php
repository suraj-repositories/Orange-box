<?php

namespace App\Models;

use App\Services\Impl\FileServiceImpl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class File extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'file_path',
        'file_name',
        'file_size',
        'is_temp',
        'mime_type',
        'fileable_type',
        'fileable_id',
    ];

    public function fileable()
    {
        return $this->morphTo();
    }
    public function getFileUrl()
    {
        if (Storage::disk('public')->exists($this->file_path)) {
            return url('storage/' . $this->file_path);
        } else if (Storage::disk('private')->exists($this->file_path)) {
            return URL::temporarySignedRoute(
                'secure.file.show',
                now()->addMinutes(10),
                ['file' => $this->id]
            );
        }
        // return config('constants')['DEFAULT_NO_FILE_IMAGE'];
        return 'https://placehold.co/50x90';
    }

    public function extension()
    {
        if ($this->file_path && Storage::disk('private')->exists($this->file_path)) {
            return strtoupper(pathinfo($this->file_path, PATHINFO_EXTENSION)) ?: null;
        }
        return null;
    }

    public function extensionIcon()
    {
        $fileService = new FileServiceImpl();
        return $fileService->getIconFromExtension(pathinfo($this->file_path, PATHINFO_EXTENSION) ?? null);
    }

    public function size()
    {
        $fileService = new FileServiceImpl();
        return $fileService->getFormattedSize($this->file_size);
    }
}
