<?php

namespace App\Http\Controllers\Admin\Docs;

use App\Http\Controllers\Controller;
use App\Models\DocumentationTemplate;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TemplateController extends Controller
{
    public function __construct(public FileService $fileService) {}
    //
    public function index()
    {
        $templates = DocumentationTemplate::latest()->paginate(10);
        $title = "Templates";

        return view('admin.docs.template.template-list', compact('templates', 'title'));
    }

    public function create()
    {
        return view('admin.docs.template.template-form');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'title' => 'required|string|max:255',

            'key' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('documentation_templates', 'key'),
            ],

            'preview_url' => 'nullable|url:http,https',
            'description' => 'nullable|string',

            'original_price' => 'nullable|numeric|min:0|gt:price',
            'price' => 'nullable|numeric|min:0|lt:original_price',

            'preview_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'original_price.gt' => 'Original price must be greater than the discounted price.',
            'price.lt' => 'Discounted price must be less than the original price.',
        ]);

        $validated['key'] = $validated['key'] ?? Str::slug($validated['title']);
        $validated['key'] = strtolower($validated['key']);

        $previewImage = null;
        if ($request->hasFile('preview_image')) {
            $previewImage = $this->fileService->uploadFile($request->preview_image, 'documentation/template');
        }

        $template = DocumentationTemplate::create([
            'title' => $validated['title'],
            'key' => $validated['key'],
            'preview_url' => $validated['preview_url'],
            'description' => $validated['description'],
            'original_price' => $validated['original_price'],
            'price' => $validated['price'],
            'preview_image' => $previewImage,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $template->files()->create([
                    'file_path' => $this->fileService->uploadFile($file, 'daily_digests'),
                    'file_name' => $this->fileService->getFileName($file),
                    'mime_type' => $this->fileService->getMimeType($file),
                    'file_size' => $file->getSize() ?? null,
                    'user_id' => $user->id
                ]);
            }
        }

        return redirect()->back()->with('success', 'Template Created Successfully!');
    }

    public function edit(DocumentationTemplate $template)
    {
        return view('admin.docs.template.template-form', compact('template'));
    }

    public function update(DocumentationTemplate $template, Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',

            'key' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('documentation_templates', 'key')
                    ->ignore($template->id),
            ],
            'preview_url' => 'nullable|url:http,https',
            'description' => 'nullable|string',

            'original_price' => 'nullable|numeric|min:0|gt:price',
            'price' => 'nullable|numeric|min:0|lt:original_price',


            'preview_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ],  [
            'original_price.gt' => 'Original price must be greater than the discounted price.',
            'price.lt' => 'Discounted price must be less than the original price.',
        ]);

        $validated['key'] = strtolower($validated['key'] ?? Str::slug($validated['title']));

        if ($request->hasFile('preview_image')) {

            if (!empty($template->preview_image)) {
                $this->fileService->deleteIfExists($template->preview_image);
            }

            $validated['preview_image'] = $this->fileService
                ->uploadFile($request->file('preview_image'), 'documentation/template');
        }

        $template->update([
            'title' => $validated['title'],
            'key' => $validated['key'],
            'preview_url' => $validated['preview_url'],
            'description' => $validated['description'] ?? null,
            'original_price' => $validated['original_price'] ?? null,
            'price' => $validated['price'] ?? null,
            'preview_image' => $validated['preview_image'] ?? $template->preview_image,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $template->files()->create([
                    'file_path' => $this->fileService->uploadFile($file, 'documentation/template'),
                    'file_name' => $this->fileService->getFileName($file),
                    'mime_type' => $this->fileService->getMimeType($file),
                    'file_size' => $file->getSize() ?? null,
                    'user_id' => $user->id,
                ]);
            }
        }

        return redirect()
            ->back()
            ->with('success', 'Template Updated Successfully!');
    }

    public function show(DocumentationTemplate $template)
    {
        return view('admin.docs.template.show-template', $template);
    }

    public function updateStatus(Request $request, DocumentationTemplate $template)
    {
        $validator = Validator::make($request->all(), [
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'status' => $template->is_active
            ]);
        }

        $template->update([
            'is_active' => $request->is_active
        ]);

        return response()->json([
            'success' => true,
            'status' => $template->is_active
        ]);
    }
}
