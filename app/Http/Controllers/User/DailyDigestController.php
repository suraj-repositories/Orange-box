<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DailyDigest;
use App\Models\User;
use App\Rules\DescriptionLength;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SweetAlert2\Laravel\Swal;

class DailyDigestController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $digestions = DailyDigest::paginate(15);

        return view('user.thinkspace.daily_digest.daily_digestion_list', compact('digestions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($userid)
    {
        //
        return view('user.thinkspace.daily_digest.daily_digest_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string',
            'sub_title' => 'nullable|string',
            'description' => ['nullable', 'string', new DescriptionLength()],
            'media_files' => 'nullable|array',
            'media_files.*' => 'file|max:' . config('validation_rules.max_file_size')
        ]);

        $dailyDigest = DailyDigest::create([
            'title' => $request->input('title'),
            'sub_title' => $request->input('sub_title', null),
            'description' => $request->input('description', null)
        ]);

        if ($request->has('media_files')) {
            $media = $request->media_files;
            if (is_array($media)) {
                foreach ($media as $file) {
                    $dailyDigest->files()->create([
                        'file_path' => $this->fileService->uploadFile($file, 'daily_digests'),
                        'file_name' => $this->fileService->getFileName($file),
                        'mime_type' => $this->fileService->getMimeType($file),
                    ]);
                }
            } else {
                $dailyDigest->files()->create([
                    'file_path' => $this->fileService->uploadFile($media, 'daily_digests'),
                    'file_name' => $this->fileService->getFileName($media),
                    'mime_type' => $this->fileService->getMimeType($media),
                ]);
            }
        }

       Swal::success([
                'title' => 'Digestion Created Successfully!',
            ]);
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show($id, DailyDigest $dailyDigest)
    {
        //
        $media = $this->fileService->getMediaMetadata($dailyDigest->files);
        return view('user.thinkspace.daily_digest.show_daily_digest', compact('dailyDigest', 'media'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id, DailyDigest $dailyDigest)
    {
        $media = $this->fileService->getMediaMetadata($dailyDigest->files);
        return view('user.thinkspace.daily_digest.daily_digest_form', compact('dailyDigest', 'media'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
