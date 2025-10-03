<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\FolderFactory;
use App\Models\Icon;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class FolderFactoryController extends Controller
{
    //
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function index(User $user)
    {
        $icons = Icon::where('category', 'folder')->where('status', 'active')->orderBy('order', 'asc')->get();

        $folderFactories = FolderFactory::with('icon')
            ->withSum('files', 'file_size')
            ->where('user_id', $user->id)
            ->orderBy('name', 'asc')
            ->paginate(15);

        $folderFactories->getCollection()->transform(function ($folder) {
            $folder->readable_file_size = $this->fileService->getFormattedSize($folder->files_sum_file_size ?? 0);
            return $folder;
        });

        $totalFilesAndSize = File::where('user_id', $user->id)
            ->where('fileable_type', FolderFactory::class)
            ->groupBy('user_id')
            ->select(
                'user_id',
                DB::raw("SUM(file_size) as total_size"),
                DB::raw("COUNT(id) as total_files")
            )
            ->first();

        return view('user.orbit_zone.folder_factory.folder_factory_list', compact('icons', 'folderFactories', 'totalFilesAndSize'));
    }

    public function create()
    {
        return view('user.orbit_zone.folder_factory.file_upload_form');
    }

    public function store(User $user, Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'max:255',
                'string',
                Rule::unique('folder_factories')->where(function ($query) use ($user) {
                    return $query->where('user_id', $user->id);
                }),
            ],
            'icon' => 'nullable|integer|exists:icons,id',
        ]);

        $folderFactory = FolderFactory::create([
            'name' => $validated['name'],
            'user_id' => $user->id,
            'icon_id' => $validated['icon'] ?? null,
            'slug' => Str::slug($validated['name'])
        ]);
        return redirect()->back()->with('success', 'Folder Created Successfully!');
    }

    /* ================================ BEGIN - CHUNK UPLOAD ================================*/

    public function uploadStatus(Request $request)
    {
        $fileName = $request->query('fileName');
        if (!$fileName) {
            return response()->json(['error' => 'Invalid file name'], 400);
        }

        $disk = Storage::disk('private');
        $tempDir = $disk->path('files/temp/' . $fileName);

        if (!file_exists($tempDir)) {
            return response()->json(['uploadedChunks' => []]);
        }

        $uploadedChunks = array_diff(scandir($tempDir), ['.', '..']);
        return response()->json(['uploadedChunks' => array_map('intval', $uploadedChunks)]);
    }



    public function uploadChunk(Request $request)
    {
        $fileName = $request->get('fileName');
        $chunkIndex = $request->get('chunkIndex');
        $totalChunks = $request->get('totalChunks');

        if (!$fileName || $chunkIndex === null || !$totalChunks || !$request->hasFile('file')) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        $disk = Storage::disk('private');
        $tempDir = $disk->path('files/temp/' . $fileName);

        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $file = $request->file('file');
        $file->move($tempDir, $chunkIndex);

        if (count(scandir($tempDir)) - 2 === (int) $totalChunks) {
            $finalFileName = "F-" . rand(100, 999) . $fileName;
            $finalPath = $disk->path('files/' . $finalFileName);

            $finalFile = fopen($finalPath, 'w');

            for ($i = 0; $i < $totalChunks; $i++) {
                $chunkPath = $tempDir . '/' . $i;
                $chunk = fopen($chunkPath, 'r');
                while ($data = fread($chunk, 1024)) {
                    fwrite($finalFile, $data);
                }
                fclose($chunk);
                unlink($chunkPath);
            }

            fclose($finalFile);
            rmdir($tempDir);

            $mimeType = $this->fileService->getFileMimeTypeByPath($finalPath);

            File::create([
                'file_path' => 'files/' . $finalFileName,
                'file_name' => substr($fileName, strpos($fileName, '_x_') + 3),
                'mime_type' => $mimeType,
                'file_size' => $disk->size('files/' . $finalFileName) ?? null,
            ]);

            return response()->json(['message' => 'Upload complete']);
        }

        return response()->json(['message' => 'Chunk uploaded']);
    }


    public function cancelUpload(Request $request)
    {
        $fileName = $request->input('fileName');
        if (!$fileName) {
            return response()->json(['error' => 'Invalid file name'], 400);
        }

        $disk = Storage::disk('private');
        $dir = $disk->path('files/temp/' . $fileName);

        $this->fileService->deleteDirectoryIfExists($dir);

        return response()->json([
            'success' => true,
            'message' => 'File upload canceled!'
        ]);
    }

    /* ================================ CHUNK UPLOAD ================================ */
}
