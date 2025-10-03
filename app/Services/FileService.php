<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

interface FileService
{
    function uploadFile(UploadedFile $file, string $folder = "uploads", string $disk = "public"): string;

    function fileExists($filePath, string $disk = "public");

    function deleteIfExists($filePath);

    function getFileName(UploadedFile $file): string;

    function getExtension(UploadedFile $file): string;

    function getMimeType(UploadedFile $file): string;

    function getFileNameByPath($filePath): string;

    function getFileMimeTypeByPath($filePath): string;

    function getExtensionByPath($filePath): string;

    function getIconFromExtension($extension): string;

    function getAllAvailableIcons(): array;

    function getSizeByPath($filePath): string;

    function getFormattedSize($sizeInBytes): string;

    function getMediaMetadata($files): array;

    function deleteDirectoryIfExists($dir);

}
