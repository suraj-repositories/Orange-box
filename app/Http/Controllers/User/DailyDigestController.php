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
    public function create()
    {
        //
        return view('user.thinkspace.daily_digest.daily_digest_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(User $user, Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string',
            'sub_title' => 'nullable|string',
            'description' => ['nullable', 'string', new DescriptionLength()],
            'media_files' => 'nullable|array',
            'media_files.*' => 'file|max:' . config('validation_rules.max_file_size')
        ]);

        $dailyDigest = new DailyDigest();
        $dailyDigest->user_id = $user->id;
        $dailyDigest->title = $request->input('title');
        $dailyDigest->sub_title = $request->input('sub_title', null);
        $dailyDigest->description =  $request->input('description', null);
        $dailyDigest->save();

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
        return redirect()->to(authRoute('user.daily-digest.show', ['dailyDigest' => $dailyDigest]));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, DailyDigest $dailyDigest)
    {
        if($dailyDigest->user_id != $user->id && !$user->hasRole('admin')){
            abort(403, "Access Denied!");
        }

        $media = $this->fileService->getMediaMetadata($dailyDigest->files);
        return view('user.thinkspace.daily_digest.show_daily_digest', compact('dailyDigest', 'media'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user, DailyDigest $dailyDigest)
    {
        if($dailyDigest->user_id != $user->id && !$user->hasRole('admin')){
            abort(403, "Access Denied!");
        }

        $media = $this->fileService->getMediaMetadata($dailyDigest->files);
        return view('user.thinkspace.daily_digest.daily_digest_form', compact('dailyDigest', 'media'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(User $user, DailyDigest $dailyDigest, Request $request)
    {
        //
         if($dailyDigest->user_id != $user->id && !$user->hasRole('admin')){
            abort(403, "Access Denied!");
        }

        $validated = $request->validate([
            'title' => 'required|string',
            'sub_title' => 'nullable|string',
            'description' => ['nullable', 'string', new DescriptionLength()],
            'media_files' => 'nullable|array',
            'media_files.*' => 'file|max:' . config('validation_rules.max_file_size')
        ]);

        $dailyDigest->title = $validated['title'];
        $dailyDigest->sub_title = $validated['sub_title'];
        $dailyDigest->description = $validated['description'];

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

        $dailyDigest->save();
        Swal::success([
                'title' => 'Digestion Updated Successfully!',
            ]);
        return redirect()->to(authRoute('user.daily-digest.show', ['dailyDigest' => $dailyDigest]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, DailyDigest $dailyDigest)
    {
        //
         if($dailyDigest->user_id != $user->id && !$user->hasRole('admin')){
            abort(403, "Access Denied!");
        }

        $dailyDigest->delete();
        Swal::success([
            'title' => 'Digestion Deleted Successfully!',
        ]);
        return redirect()->to(authRoute('user.daily-digest'));
    }
}
