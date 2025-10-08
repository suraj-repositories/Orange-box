<?php

namespace App\Services\Impl;

use App\Services\FileService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileServiceImpl implements FileService
{

    public function uploadFile(UploadedFile $file, string $folder = "uploads", string $disk = "public"): string
    {
        if ($file->getSize() > 0) {
            return $file->store($folder, $disk);
        }
        $uniqueFileName = pathinfo($this->getFileName($file), PATHINFO_FILENAME) . '-' . time() . '-' . uniqid() . '.' . $this->getExtension($file);
        return $file->storeAs($folder, $uniqueFileName, $disk);
    }


    public function fileExists($filePath, $disk = 'public')
    {
        if ($filePath && Storage::disk($disk)->exists($filePath)) {
            return true;
        }
        return false;
    }

    function deleteIfExists($filePath)
    {
        if ($filePath && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
            return 1;
        }
        return 0;
    }

    function deleteAllIfExists(array $filePaths): int{
        $deleted = 0;
        foreach($filePaths as $filePath){
            $deleted += $this->deleteIfExists($filePath);
        }
        return $deleted;
    }

    public function getFileName(UploadedFile $file): string
    {
        return $file->getClientOriginalName();
    }

    public function getExtension(UploadedFile $file): string
    {
        return $file->getClientOriginalExtension();
    }

    public function getMimeType(UploadedFile $file): string
    {
        return $file->getClientMimeType() ?? 'Unknown';
    }

    public function getFileNameByPath($filePath): string
    {
        return pathinfo($filePath, PATHINFO_FILENAME) ?? "-";
    }

    public function getFileMimeTypeByPath($filePath): string
    {
        return mime_content_type($filePath) ?? "Unknown";
    }

    public function getExtensionByPath($filePath): string
    {
        return pathinfo($filePath, PATHINFO_EXTENSION) ?? null;
    }

    public function getIconFromExtension($extension): string
    {
        return config('extension')['icons'][$extension] ?? config('extension')['DEFAULT_FILE_ICON'];
    }

    public function getAllAvailableIcons(): array
    {
        return config('extension')['icons'] ?? [];
    }

    public function getSizeByPath($filePath): string
    {
        if (!file_exists($filePath)) {
            return "File does not exist.";
        }
        return $this->getFormattedSize(filesize($filePath));
    }

    public function getFormattedSize($sizeInBytes): string
    {
        $size = $sizeInBytes;
        $units = ['Bytes', 'KB', 'MB', 'GB', 'TB'];

        $unitIndex = 0;
        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2) . ' ' . $units[$unitIndex];
    }

    public function getMediaMetadata($files): array
    {
        $media = [];
        foreach ($files as $file) {
            $filePath = Storage::disk('public')->path($file->file_path);

            $data = [
                'file_id' => $file->id,
                'file_name' => $file->file_name,
                'extension' => strtoupper($this->getExtensionByPath($filePath) ?? "-"),
                'file_path' => $file->getFileUrl(),
                'file_icon_class' => $this->getIconFromExtension($this->getExtensionByPath($filePath) ?? "-"),
                'type' => 'Unknown',
                'size' => '0 Bits',
                'is_available' => false,
                'is_image' => false,
            ];

            if (Storage::disk('public')->exists($file->file_path)) {
                $data['type'] = $this->getFileMimeTypeByPath($filePath);
                $data['size'] = $this->getSizeByPath($filePath);
                $data['is_available'] = true;
                $data['is_image'] = in_array(strtolower($data['extension']), config('extension.images', []));
            }

            $media[] = $data;
        }

        return $media;
    }

    function deleteDirectoryIfExists($dir)
    {
        if (!file_exists($dir)) {
            return false;
        }

        if (is_dir($dir)) {
            $files = array_diff(scandir($dir), array('.', '..'));
            foreach ($files as $file) {
                $filePath = $dir . DIRECTORY_SEPARATOR . $file;
                if (is_dir($filePath)) {
                    $this->deleteDirectoryIfExists($filePath);
                } else {
                    unlink($filePath);
                }
            }
            return rmdir($dir);
        }
        return false;
    }
}
