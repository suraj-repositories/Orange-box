<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\User;
use App\Services\FileService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DocumentationController extends Controller
{
    public function __construct(public FileService $fileService) {}

    //
    public function index()
    {
        $documentations = Documentation::with('user')->latest()->paginate();

        return view('user.documentation.documentation_list', [
            'title' => 'Documenations',
            'documentations' => $documentations
        ]);
    }

    public function create(User $user)
    {
        $urlPrefix = rtrim(config('app.url'), '/') . '/' . $user->username . '/docs/';
        return view(
            'user.documentation.documentation_form',
            [
                'urlPrefix' => $urlPrefix
            ]
        );
    }

    public function store(User $user, Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:256',
            'url' => 'required|string|max:256',
            'logo' => 'nullable|file'
        ]);

        try {
            $slugUrl = Str::slug($validated['url']);
            $logo = null;
            if ($request->has('logo')) {
                $logo = $this->fileService->uploadFile($request->logo, 'documentations');
            }

            Documentation::create([
                'user_id' => $user->id,
                'title' => $validated['title'],
                'url' => $slugUrl,
                'logo' => $logo
            ]);

            return redirect()->to(authRoute('user.documentation.index'))->with('success', 'Documentation Created Successfully!');
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Error: ' . $ex->getMessage());
        }
    }

    public function edit(User $user, Documentation $documentation){
        if($user->id != $documentation->user_id){
            abort(403, 'Invalid Request');
        }
         $urlPrefix = rtrim(config('app.url'), '/') . '/' . $user->username . '/docs/';

        return view('user.documentation.documentation_form', [
            'documentation' => $documentation,
             'urlPrefix' => $urlPrefix
        ]);

    }

    public function update(User $user, Documentation $documentation, Request $request){
         $validated = $request->validate([
            'title' => 'required|string|max:256',
            'url' => 'required|string|max:256',
            'logo' => 'nullable|file'
        ]);

        if($documentation->user_id != $user->id){
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        try {
            $slugUrl = Str::slug($validated['url']);
            $logo = null;
            if ($request->has('logo')) {
                $logo = $this->fileService->uploadFile($request->logo, 'documentations');
                $this->fileService->deleteIfExists($documentation->logo);
                $documentation->logo = $logo;
            }

            $documentation->title = $validated['title'];
            $documentation->url = $slugUrl;
            $documentation->save();

            return redirect()->to(authRoute('user.documentation.index'))->with('success', 'Documentation Updated Successfully!');
        } catch (Exception $ex) {
            return redirect()->back()->with('error', 'Error: ' . $ex->getMessage());
        }
    }
}
