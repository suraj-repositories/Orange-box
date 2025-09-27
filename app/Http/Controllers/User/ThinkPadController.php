<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\ThinkPad;
use App\Models\ThinkSpace;
use App\Models\User;
use App\Rules\DescriptionLength;
use App\Services\FileService;
use Illuminate\Http\Request;
use SweetAlert2\Laravel\Swal;

class ThinkPadController extends Controller
{

    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    //
    public function index()
    {
        //
        $thinkpads = ThinkPad::paginate(15);

        return view('user.thinkspace.think_pad.think_pad_list', compact('thinkpads'));
    }

    public function create()
    {
        //
        return view('user.thinkspace.think_pad.think_pad_form');
    }

    public function store(User $user, Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'sub_title' => 'nullable|string',
            'description' => ['nullable', 'string', new DescriptionLength()],
            'media_files' => 'nullable|array',
            'media_files.*' => 'file|max:' . config('validation_rules.max_file_size')
        ]);

        $thinkPad = new ThinkPad();
        $thinkPad->user_id = $user->id;
        $thinkPad->title = $request->input('title');
        $thinkPad->sub_title = $request->input('sub_title', null);
        $thinkPad->description =  $request->input('description', null);

         $thinkPad->save();

         if ($request->has('media_files')) {
            $media = $request->media_files;
            if (is_array($media)) {
                foreach ($media as $file) {
                    $thinkPad->files()->create([
                        'file_path' => $this->fileService->uploadFile($file, 'think_pad'),
                        'file_name' => $this->fileService->getFileName($file),
                        'mime_type' => $this->fileService->getMimeType($file),
                        'user_id' => $user->id
                    ]);
                }
            } else {
                $thinkPad->files()->create([
                    'file_path' => $this->fileService->uploadFile($media, 'think_pad'),
                    'file_name' => $this->fileService->getFileName($media),
                    'mime_type' => $this->fileService->getMimeType($media),
                    'user_id' => $user->id
                ]);
            }
        }
        Swal::success([
            'title' => 'Success!',
            'text' => 'Pad Created Successfully!',
        ]);

        return redirect()->to(authRoute('user.daily-digest.show', ['think_pad' => $thinkPad]));

    }

    public function edit(User $user, ThinkPad $dailyDigest) {}

    public function storeEditorImages(User $user, Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json([
                'status'  => 'error',
                'message' => 'File is required!',
                'url'     => '',
            ]);
        }

        $uploadedFile = $request->file('file');

        $filePath = $this->fileService->uploadFile($uploadedFile, 'think_pad');

        if ($filePath) {
            try {
                $file = File::create([
                    'user_id'   => $user->id,
                    'file_name'     => $this->fileService->getFileName($uploadedFile),
                    'file_path'     => $filePath,
                    'mime_type'     => $this->fileService->getMimeType($uploadedFile),
                    'fileable_type' => ThinkPad::class,
                    'fileable_id'   => null,
                ]);

                return response()->json([
                    'status' => 'success',
                    'url'    => $file->getFileUrl(),
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'DB save failed: ' . $e->getMessage(),
                    'url'     => '',
                ]);
            }
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'Something went wrong while saving the file!',
            'url'     => ''
        ]);
    }
}
