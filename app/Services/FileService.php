<?php

namespace App\Services;

interface FileService
{
    function uploadFile(\Illuminate\Http\UploadedFile $file, string $folder = "uploads", string $disk = "public"): string;

    function getFileName(\Illuminate\Http\UploadedFile $file): string;

    function getMimeType(\Illuminate\Http\UploadedFile $file): string;
}
