<?php

namespace App\Services\Impl;

use App\Services\FileService;

class FileServiceImpl implements FileService
{
    public function uploadFile(\Illuminate\Http\UploadedFile $file, string $folder = "uploads", string $disk = "public"): string
    {
        return $file->store($folder, $disk);
    }

    public function getFileName(\Illuminate\Http\UploadedFile $file): string
    {
        return $file->getClientOriginalName();
    }

    public function getMimeType(\Illuminate\Http\UploadedFile $file): string
    {
        return $file->getClientMimeType();
    }
}
