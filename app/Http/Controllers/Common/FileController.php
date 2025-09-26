<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use SweetAlert2\Laravel\Swal;

class FileController extends Controller
{

    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function destroy(File $file, Request $request)
    {

        Gate::authorize('delete', $file);

        $file->delete();

        if ($request->isJson() || $request->wantsJson()) {
            return response()->json([
                'status' => 200,
                'message' => 'File Deleted Successfully!'
            ]);
        }


        Swal::success([
            'title' => 'Success!',
            'text' => 'File Deleted Successfully!',
        ]);
        return redirect()->back();
    }

    public function rename(File $file, Request $request)
    {
        $validated = $request->validate([
            'new_name' => 'required|string|max:255'
        ]);

        if ($file->file_name != $validated['new_name']) {
            $new_name = $validated['new_name'];
            $new_extension = $this->fileService->getExtensionByPath($new_name);
            $old_extension = $this->fileService->getExtensionByPath($file->file_path);
            $old_filename = pathinfo($file->file_path, PATHINFO_FILENAME);

            $new_file_path = pathinfo($file->file_path, PATHINFO_DIRNAME) . '/' . $new_name;

            if (!empty($new_extension)) {
                $new_file_path = pathinfo($file->file_path, PATHINFO_FILENAME) . '.' . $new_extension;
            } else {
                $new_file_path = pathinfo($file->file_path, PATHINFO_DIRNAME) . '/' . $old_filename . '.' . $old_extension;
            }

            if (!Storage::disk('public')->exists($file->file_path)) {
                return redirect()->back()->with('error', "File doesn't exist!");
            }

            if (Storage::disk('public')->move($file->file_path, $new_file_path)) {
                $file->file_name = $new_name;
                $file->file_path = $new_file_path;
                $file->mime_type = Storage::disk('public')->mimeType($new_file_path);
                $file->save();


                Swal::success([
                    'title' => 'Success!',
                    'text' => 'File Rename Successful!',
                ]);
                return redirect()->back()->with('success', 'File Rename Successful!');
            }

            Swal::error([
                'title' => 'Oops!',
                'text' => 'Something went wrong!',
            ]);

            return redirect()->back()->with('error', 'Something went wrong!');
        }

        return redirect()->back();
    }
}
