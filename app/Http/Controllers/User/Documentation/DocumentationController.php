<?php

namespace App\Http\Controllers\User\Documentation;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use App\Models\User;
use App\Services\FileService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DocumentationController extends Controller
{
    public function __construct(public FileService $fileService) {}

    //
    public function index()
    {
        $authUser = Auth::user();
        $documentations = Documentation::with('user')->where('user_id', $authUser->id)->latest()->paginate();

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
            'title'          => 'required|string|max:256',
            'url'            => 'required|string|max:256',
            'logo_light'     => 'nullable|image|max:2048',
            'logo_sm_light'  => 'nullable|image|max:2048',
            'logo_dark'      => 'nullable|image|max:2048',
            'logo_sm_dark'   => 'nullable|image|max:2048',
        ]);

        try {

            $slugUrl = Str::slug($validated['url']);

            $originalSlug = $slugUrl;
            $counter = 1;

            while (
                Documentation::where('user_id', $user->id)
                ->where('url', $slugUrl)
                ->exists()
            ) {
                $slugUrl = $originalSlug . '-' . $counter++;
            }

            $logoLight    = $request->file('logo_light')
                ? $this->fileService->uploadFile($request->file('logo_light'), 'documentations')
                : null;

            $logoSmLight  = $request->file('logo_sm_light')
                ? $this->fileService->uploadFile($request->file('logo_sm_light'), 'documentations')
                : null;

            $logoDark     = $request->file('logo_dark')
                ? $this->fileService->uploadFile($request->file('logo_dark'), 'documentations')
                : null;

            $logoSmDark   = $request->file('logo_sm_dark')
                ? $this->fileService->uploadFile($request->file('logo_sm_dark'), 'documentations')
                : null;

            Documentation::create([
                'user_id'       => $user->id,
                'title'         => $validated['title'],
                'url'           => $slugUrl,
                'logo_light'    => $logoLight,
                'logo_sm_light' => $logoSmLight,
                'logo_dark'     => $logoDark,
                'logo_sm_dark'  => $logoSmDark,
            ]);

            return redirect()
                ->to(authRoute('user.documentation.index'))
                ->with('success', 'Documentation Created Successfully!');
        } catch (Exception $ex) {

            return back()->withInput()
                ->with('error', 'Error: ' . $ex->getMessage());
        }
    }

    public function show(User $user, Documentation $documentation)
    {
        $title = $documentation->title;
        return view('user.documentation.documentation_show', compact('user', 'documentation', 'title'));
    }

    public function edit(User $user, Documentation $documentation)
    {
        if ($user->id != $documentation->user_id) {
            abort(403, 'Invalid Request');
        }
        $urlPrefix = rtrim(config('app.url'), '/') . '/' . $user->username . '/docs/';

        return view('user.documentation.documentation_form', [
            'documentation' => $documentation,
            'urlPrefix' => $urlPrefix
        ]);
    }

    public function update(User $user, Documentation $documentation, Request $request)
    {
        if ($documentation->user_id !== $user->id) {
            return back()->with('error', 'Unauthorized access');
        }

        $validated = $request->validate([
            'title'          => 'required|string|max:256',
            'url'            => 'required|string|max:256',
            'logo_light'     => 'nullable|image|max:2048',
            'logo_sm_light'  => 'nullable|image|max:2048',
            'logo_dark'      => 'nullable|image|max:2048',
            'logo_sm_dark'   => 'nullable|image|max:2048',
        ]);

        try {

            $slugUrl = Str::slug($validated['url']);

            $originalSlug = $slugUrl;
            $counter = 1;

            while (
                Documentation::where('user_id', $user->id)
                ->where('url', $slugUrl)
                ->where('id', '!=', $documentation->id)
                ->exists()
            ) {
                $slugUrl = $originalSlug . '-' . $counter++;
            }

            $documentation->title = $validated['title'];
            $documentation->url   = $slugUrl;

            $logoFields = [
                'logo_light',
                'logo_sm_light',
                'logo_dark',
                'logo_sm_dark',
            ];

            foreach ($logoFields as $field) {

                if ($request->hasFile($field)) {

                    $newPath = $this->fileService
                        ->uploadFile($request->file($field), 'documentations');

                    if ($documentation->{$field}) {
                        $this->fileService->deleteIfExists($documentation->{$field});
                    }

                    $documentation->{$field} = $newPath;
                }
            }

            $documentation->save();

            return redirect()
                ->to(authRoute('user.documentation.index'))
                ->with('success', 'Documentation Updated Successfully!');
        } catch (Exception $ex) {

            return back()->withInput()
                ->with('error', 'Error: ' . $ex->getMessage());
        }
    }
}
