<?php

namespace App\Http\Controllers\User\Documentation;

use App\Http\Controllers\Controller;
use App\Jobs\SyncDocumentationPageJob;
use App\Models\Documentation;
use App\Models\DocumentationDocument;
use App\Models\DocumentationPage;
use App\Models\DocumentationRelease;
use App\Models\DocumentationTemplate;
use App\Models\TemplatePurchase;
use App\Models\User;
use App\Services\FileService;
use App\Services\GitService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class DocumentationController extends Controller
{
    public function __construct(public FileService $fileService, public GitService $gitService) {}

    //
    public function index()
    {
        $authUser = Auth::user();
        $documentations = Documentation::with('user', 'latestRelease')
            ->where('user_id', $authUser->id)
            ->latest()
            ->paginate();

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
            'template_id'    => 'nullable|numeric|exists:documentation_templates,id'
        ]);

        try {

            $slugUrl = Str::slug($validated['url']);

            $templateId = null;
            if ($validated['template_id']) {
                $template = DocumentationTemplate::where('id', $validated['template_id'])->where('is_active', true)->first();

                $isPurchased = TemplatePurchase::where('documentation_template_id', $validated['template_id'])
                    ->where('user_id', $user->id)
                    ->where('payment_status', 'paid')
                    ->exists();

                if (($template->price ?? 0) > 0 && !$isPurchased) {
                    return redirect()->back()->with('error', 'Unpurchased template selected!');
                }

                $templateId = $template->id;
            }

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

            $documentation = Documentation::create([
                'user_id'       => $user->id,
                'title'         => $validated['title'],
                'url'           => $slugUrl,
                'logo_light'    => $logoLight,
                'logo_sm_light' => $logoSmLight,
                'logo_dark'     => $logoDark,
                'logo_sm_dark'  => $logoSmDark,
                'documentation_template_id' => $templateId
            ]);

            DocumentationRelease::create([
                'documentation_id' => $documentation->id,
                'title' => 'Beta',
                'version' => 'v1.0.0',
                'is_current' => true,
                'is_published' => true
            ]);

            return redirect()
                ->to(authRoute('user.documentation.index'))
                ->with('success', 'Documentation Created Successfully!');
        } catch (Exception $ex) {

            return back()->withInput()
                ->with('error', 'Error: ' . $ex->getMessage());
        }
    }

    public function showLatestRelease(User $user, Documentation $documentation)
    {
        $release = $documentation->releases()->latest('id')->first();
        return redirect()->to(authRoute('user.documentation.show', [
            'documentation' => $documentation,
            'release' => $release
        ]));
    }

    public function show(User $user, Documentation $documentation, DocumentationRelease $release)
    {
        $title = $documentation->title;

        $documentationDocuments = DocumentationDocument::where('documentation_id', $documentation->id)
            ->where('release_id', $release->id)
            ->select('status', 'type', 'id')
            ->get()
            ->keyBy('type');

        return view('user.documentation.documentation_show', compact('user', 'documentation', 'title', 'release', 'documentationDocuments'));
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
            'template_id'    => 'nullable|numeric|exists:documentation_templates,id'
        ]);

        try {

            $slugUrl = Str::slug($validated['url']);

            $originalSlug = $slugUrl;
            $counter = 1;

            $templateId = null;
            if ($validated['template_id']) {
                $template = DocumentationTemplate::where('id', $validated['template_id'])->where('is_active', true)->first();

                $isPurchased = TemplatePurchase::where('documentation_template_id', $validated['template_id'])
                    ->where('user_id', $user->id)
                    ->where('payment_status', 'paid')
                    ->exists();

                if (($template->price ?? 0) > 0 && !$isPurchased) {
                    return redirect()->back()->with('error', 'Unpurchased template selected!');
                }

                $templateId = $template->id;
            }


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
            $documentation->documentation_template_id = $templateId;


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
            return back()->withInput()->with('error', 'Error: ' . $ex->getMessage());
        }
    }

    public function importGithub(
        User $user,
        Documentation $documentation,
        DocumentationRelease $release,
        Request $request
    ) {
        $validator = Validator::make($request->all(), [
            'github_url' => ['required', 'url'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        try {

            $this->gitService->loadEntireDocumentation(
                $request->github_url,
                $documentation,
                $release,
                $user
            );

            return response()->json([
                'success' => true,
                'message' => 'Documentation synchronization started successfully.',
            ]);
        } catch (Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e instanceof ValidationException
                    ? $e->validator->errors()->first()
                    : "Please delete the previous files first!",

                'error' => $e instanceof ValidationException
                    ? $e->validator->errors()->first()
                    : $e->getMessage()

            ]);
        }
    }

    public function syncPages(
        User $user,
        Documentation $documentation,
        DocumentationRelease $release,

    ) {
        try {
            if (!empty($release->sync_batch_id)) {
                $batch = Bus::findBatch($release->sync_batch_id);

                if ($batch && !$batch->finished()) {
                    throw ValidationException::withMessages([
                        'sync' => 'Documentation is already syncing.',
                    ]);
                }
            }

            $jobs = DocumentationPage::query()
                ->where('type', 'file')
                ->whereNotNull('git_link')
                ->where('user_id', $user->id)
                ->where('documentation_id', $documentation->id)
                ->where('release_id', $release->id)
                ->get()
                ->map(fn($page) => new SyncDocumentationPageJob($page->id))
                ->toArray();

            $batch = Bus::batch($jobs)
                ->name("Documentation Sync {$documentation->id}")
                ->allowFailures()
                ->then(function () use ($release) {
                    $release->update([
                        'sync_status' => 'completed',
                        'sync_batch_id' => null,
                        'last_synced_at' => now(),
                        'sync_error' => null,
                    ]);
                })
                ->catch(function ($batch, Throwable $exception) use ($release) {
                    $release->update([
                        'sync_status' => 'failed',
                        'sync_batch_id'    => null,
                        'sync_error'  => $exception->getMessage(),
                    ]);
                })
                ->finally(function () {})
                ->dispatch();

            $release->update([
                'sync_batch_id' => $batch->id,
                'sync_status' => 'syncing',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Please wait this may take some time...',
                'batch_id' => $batch->id,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function syncPageProgress(
        User $user,
        Documentation $documentation,
        DocumentationRelease $release
    ) {

        if ($release->sync_status != 'syncing' || empty($release->sync_batch_id)) {

            return response()->json([
                'success' => true,
                'running' => false,
                'message' => $release->sync_status == 'completed' ? 'Synchronization completed successfully.'
                    : 'Synchronization completed with errors.',
            ]);
        }

        $batch = Bus::findBatch($release->sync_batch_id);

        if (!$batch) {
            return response()->json([
                'success' => true,
                'running' => false,
            ]);
        }

        $completedMessage = '';

        if ($batch->finished()) {
            $release->update([
                'sync_batch_id'  => null,
                'sync_status'    => $batch->hasFailures() ? 'failed' : 'completed',
                'last_synced_at' => $batch->hasFailures() ? $release->last_synced_at : now(),
                'sync_error'     => $batch->hasFailures()
                    ? 'One or more synchronization jobs failed.'
                    : null,
            ]);

            $completedMessage = $batch->hasFailures()
                ? 'Synchronization completed with errors.'
                : 'Synchronization completed successfully.';
        }

        return response()->json([
            'success'   => true,
            'running'   => !$batch->finished(),
            'progress'  => $batch->progress(),
            'processed' => $batch->processedJobs(),
            'total'     => $batch->totalJobs,
            'pending'   => $batch->pendingJobs,
            'failed'    => $batch->failedJobs,
            'finished'  => $batch->finished(),
            'message'   => $batch->finished()
                ? $completedMessage
                : "Synchronization in progress ({$batch->progress()}%).",
        ]);
    }
}
