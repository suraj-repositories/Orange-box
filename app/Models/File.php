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
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            return asset('storage/' . $this->file_path);
        }

        if ($this->file_path && Storage::disk('private')->exists($this->file_path)) {
            return URL::temporarySignedRoute(
                'secure.file.show',
                now()->addMinutes(10),
                ['file' => $this->id]
            );
        }

        return 'https://placehold.co/50x90';
    }

    public function getRelativePath()
    {
        if (!$this->file_path) {
            return null;
        }

        if (str_starts_with($this->file_path, '/')) {
            return $this->file_path;
        }

        return '/storage/' . $this->file_path;
    }

    public function extension()
    {
        if ($this->file_path && Storage::disk('private')->exists($this->file_path)) {
            return strtoupper(pathinfo($this->file_path, PATHINFO_EXTENSION)) ?: null;
        }
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
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
