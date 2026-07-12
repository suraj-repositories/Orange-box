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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FolderFactoryController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function fileManager(User $user, Request $request)
    {
        $title = "File Manager";

        $sort = $request->sort;
        $filter = $request->filter;

        $search = trim($request->search ?? '');
        $type = $request->type;
        $modified = $request->modified;
        $location = $request->location;

        $isSearching = filled($search);


        $myDrive = FolderFactory::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'My Drive',
            'slug' => 'my-drive'
        ]);

        $foldersQuery = FolderFactory::query()
            ->select([
                'id',
                'name as item_name',
                DB::raw("'folder' as item_type"),
                DB::raw('NULL as mime_type'),
                DB::raw('NULL as file_size'),
                DB::raw('NULL as file_path'),
                DB::raw('NULL as file_name'),
                'icon_id',
                'is_favourite',
                'created_at',
                'updated_at',
            ])
            ->where('user_id', $user->id);

        $filesQuery = File::query()
            ->select([
                'id',
                'file_name as item_name',
                DB::raw("'file' as item_type"),
                'mime_type',
                'file_size',
                'file_path',
                'file_name',
                DB::raw('NULL as icon_id'),
                'is_favourite',
                'created_at',
                'updated_at',
            ])
            ->where('user_id', $user->id)
            ->where('fileable_type', FolderFactory::class)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('folder_factories')
                    ->whereColumn('folder_factories.id', 'files.fileable_id')
                    ->whereNotNull('folder_factories.deleted_at');
            });

        if ($filter === 'trash') {
            $foldersQuery->onlyTrashed();
            $filesQuery->onlyTrashed();
        } else {
            if (!$isSearching) {
                $foldersQuery->where('parent_id', $myDrive->id);
                $filesQuery->where('fileable_id', $myDrive->id);
            }
        }

        $query = DB::query()->fromSub(
            $foldersQuery->unionAll($filesQuery),
            'items'
        );

        if (!empty($search)) {
            $query->where('item_name', 'like', '%' . $search . '%');
        }

        switch ($filter) {

            case 'recent':
                $query->orderByDesc('updated_at');
                break;

            case 'folder':
                $query->where('item_type', 'folder');
                break;

            case 'favourite':
                $query->where('is_favourite', 1);
                break;
        }

        if (!empty($type)) {

            switch ($type) {

                case 'image':
                    $query->where(function ($q) {
                        $q->where('item_type', 'folder')
                            ->orWhere('mime_type', 'like', 'image/%');
                    });
                    break;

                case 'video':
                    $query->where(function ($q) {
                        $q->where('item_type', 'folder')
                            ->orWhere('mime_type', 'like', 'video/%');
                    });
                    break;

                case 'audio':
                    $query->where(function ($q) {
                        $q->where('item_type', 'folder')
                            ->orWhere('mime_type', 'like', 'audio/%');
                    });
                    break;

                case 'document':
                    $query->where(function ($q) {
                        $q->where('item_type', 'folder')
                            ->orWhere('mime_type', 'like', 'application/pdf%')
                            ->orWhere('mime_type', 'like', 'application/msword%')
                            ->orWhere('mime_type', 'like', 'application/vnd%')
                            ->orWhere('mime_type', 'like', 'text/%');
                    });
                    break;

                case 'archive':
                    $query->where(function ($q) {
                        $q->where('item_type', 'folder')
                            ->orWhere('mime_type', 'like', '%zip%')
                            ->orWhere('mime_type', 'like', '%rar%')
                            ->orWhere('mime_type', 'like', '%7z%');
                    });
                    break;

                case 'other':
                    $query->where(function ($q) {
                        $q->where('item_type', 'folder')
                            ->orWhere(function ($file) {
                                $file->where('mime_type', 'not like', 'image/%')
                                    ->where('mime_type', 'not like', 'video/%')
                                    ->where('mime_type', 'not like', 'audio/%');
                            });
                    });
                    break;
            }
        }

        if (!empty($modified)) {

            switch ($modified) {

                case 'today':
                    $query->whereDate('updated_at', today());
                    break;

                case '7_days':
                    $query->where('updated_at', '>=', now()->subDays(7));
                    break;

                case '30_days':
                    $query->where('updated_at', '>=', now()->subDays(30));
                    break;

                case '90_days':
                    $query->where('updated_at', '>=', now()->subDays(90));
                    break;

                case 'year':
                    $query->whereYear('updated_at', now()->year);
                    break;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Location Filter
        |--------------------------------------------------------------------------
        */

        if ($location === 'favorites') {
            $query->where('is_favourite', 1);
        }

        switch ($sort) {

            case 'created_at':
                $query->orderByDesc('created_at');
                break;

            case 'updated_at':
                $query->orderByDesc('updated_at');
                break;

            case 'name':
            default:
                $query->orderBy('item_name');
                break;
        }

        $totalItems = $query->clone()->count();

        $items = $query->paginate(16, ['*'], 'items_page')
            ->withQueryString();

        $recentItems = DB::query()
            ->fromSub(
                $foldersQuery->clone()->union(
                    File::query()
                        ->select([
                            'id',
                            'file_name as item_name',
                            DB::raw("'file' as item_type"),
                            'mime_type',
                            'file_size',
                            'file_path',
                            'file_name',
                            DB::raw('NULL as icon_id'),
                            'is_favourite',
                            'created_at',
                            'updated_at',
                        ])
                        ->where('user_id', $user->id)
                        ->where('fileable_type', FolderFactory::class)
                ),
                'items'
            )
            ->orderByDesc('updated_at')
            ->take(8)
            ->get();

        $items->getCollection()->transform(function ($item) use ($request) {
            return $this->bindFileData($item, $request->filter == 'trash');
        });

        $recentItems->transform(function ($item) {
            return $this->bindFileData($item);
        });

        $icons = Icon::where('category', 'folder')
            ->where('status', 'active')
            ->orderBy('order', 'asc')
            ->get();

        $totalLimit = config('app.per_user_storage');

        $totalUsed = File::where('user_id', $user->id)
            ->where('fileable_type', FolderFactory::class)
            ->sum('file_size');

        $storageStats = [
            'used' => $this->fileService->getFormattedSize($totalUsed),
            'used_in_bytes' => $totalUsed,
            'limit' => $this->fileService->getFormattedSize($totalLimit),
            'percentage' => ($totalLimit > 0)
                ? round(($totalUsed / $totalLimit) * 100, 1)
                : 0,
        ];

        $fileStats = $this->fileService->getFileStats($user);

        $usedBytes = max($storageStats['used_in_bytes'], 1);

        $photoPercent = round(($fileStats['photos']['size_in_bytes'] / $usedBytes) * 100, 1);
        $videoPercent = round(($fileStats['videos']['size_in_bytes'] / $usedBytes) * 100, 1);
        $documentPercent = round(($fileStats['documents']['size_in_bytes'] / $usedBytes) * 100, 1);
        $otherPercent = round(($fileStats['others']['size_in_bytes'] / $usedBytes) * 100, 1);

        $folderFactories = FolderFactory::where('user_id', $user->id)->get();

        $isSearch = $request->filled('search')
            || $request->filled('type')
            || $request->filled('modified')
            || $request->filled('location');

        return view(
            'user.orbit_zone.folder_factory.file-manager',
            compact(
                'title',
                'items',
                'recentItems',
                'icons',
                'user',
                'fileStats',
                'photoPercent',
                'videoPercent',
                'documentPercent',
                'otherPercent',
                'storageStats',
                'totalItems',
                'folderFactories',
                'isSearch'
            )
        );
    }

    public function bindFileData($item, $withTrashed = false)
    {

        if ($item->item_type === 'file') {

            $item->file_url = $this->fileService->getFullFileUrl($item->id, $item->file_path);
            $extension =  $this->fileService->getExtensionByPath($item->file_path);
            $item->extension =  $extension;
            $item->extension_icon = $this->fileService->getIconFromExtension($extension);
            $item->formatted_file_size = $this->fileService->getFormattedSize($item->file_size ?? 0);
        } elseif ($item->item_type === 'folder') {


            $item->icon_url = asset(config('constants.DEFAULT_FOLDER_ICON'));
            if (!empty($item->icon_id)) {
                $icon = Icon::where('id', $item->icon_id)->first();
                if ($icon) {
                    $item->icon_url = $icon->getUrl();
                }
            }

            $fileCount = File::where('fileable_type', FolderFactory::class)
                ->where('user_id', Auth::id())->where('fileable_id', $item->id)
                ->when($withTrashed, function ($query) {
                    $query->withTrashed();
                })
                ->count();

            $folderCount = FolderFactory::where('parent_id', $item->id)->where('user_id', Auth::id())->when($withTrashed, function ($query) {
                $query->withTrashed();
            })->count();

            $item->item_count = $fileCount + $folderCount;
        }

        return $item;
    }

    public function toggleFavourite(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_type'    => 'required|in:folder,file',
            'item_id'      => 'required|integer',
            'is_favourite' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $isFavourite = $request->boolean('is_favourite');

        $model = $request->item_type === 'file'
            ? File::class
            : FolderFactory::class;

        $updated = $model::where('id', $request->item_id)
            ->where('user_id', $user->id)
            ->update([
                'is_favourite' => $isFavourite,
            ]);

        if (! $updated) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.',
            ], 404);
        }

        return response()->json([
            'success'       => true,
            'message'       => 'Updated successfully!',
            'is_favourite'  => $isFavourite,
        ]);
    }

    public function copyFile(User $user, File $file, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'folder_id' => 'required|exists:folder_factories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $folder = FolderFactory::where('id', $request->folder_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$folder) {
            return response()->json([
                'success' => false,
                'message' => 'Folder not found.',
            ], 404);
        }

        DB::beginTransaction();

        try {
            $extension = pathinfo($file->file_path, PATHINFO_EXTENSION);
            $newPath = 'uploads/' . $user->id . '/' . Str::uuid() . '.' . $extension;

            Storage::copy($file->file_path, $newPath);
            $copiedFile = File::create([
                'user_id'       => $user->id,
                'file_path'     =>   $newPath,
                'file_name'     => $file->file_name,
                'mime_type'     => $file->mime_type,
                'file_size'     => $file->file_size,
                'is_temp'       => 0,
                'fileable_type' => FolderFactory::class,
                'fileable_id'   => $folder->id,
                'is_favourite'  => 0,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'File copied successfully.',
                'file'    => $copiedFile,
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function downloadFile(User $user, File $file, Request $request)
    {
        if ($user->id != $file->user_id) {
            abort(404);
        }
        return response()->download($file->file_url);
    }

    public function moveFile(User $user, File $file, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'folder_id' => 'required|exists:folder_factories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        if ((int) $file->user_id !== (int) $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied.',
            ], 403);
        }

        $folder = FolderFactory::where('id', $request->folder_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$folder) {
            return response()->json([
                'success' => false,
                'message' => 'Folder not found.',
            ], 404);
        }

        if (
            $file->fileable_type === FolderFactory::class &&
            $file->fileable_id == $folder->id
        ) {
            return response()->json([
                'success' => false,
                'message' => 'File is already in this folder.',
            ], 422);
        }

        $file->update([
            'fileable_type' => FolderFactory::class,
            'fileable_id'   => $folder->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'File moved successfully!',
        ]);
    }

    public function moveFolder(User $user, FolderFactory $folder, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'folder_id' => 'required|exists:folder_factories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        if ((int) $folder->user_id !== (int) $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied.',
            ], 403);
        }

        $destinationFolder = FolderFactory::where('id', $request->folder_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$destinationFolder) {
            return response()->json([
                'success' => false,
                'message' => 'Destination folder not found.',
            ], 404);
        }

        if ($folder->id === $destinationFolder->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot move a folder into itself.',
            ], 422);
        }

        $folder->update([
            'parent_id' => $destinationFolder->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Folder moved successfully!',
        ]);
    }

    public function copyFolder(User $user, FolderFactory $folder, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'folder_id' => 'required|exists:folder_factories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        if ((int) $folder->user_id !== (int) $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied.',
            ], 403);
        }

        $destinationFolder = FolderFactory::where('id', $request->folder_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$destinationFolder) {
            return response()->json([
                'success' => false,
                'message' => 'Destination folder not found.',
            ], 404);
        }

        DB::beginTransaction();

        try {

            $this->duplicateFolder($folder, $destinationFolder->id, $user->id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Folder copied successfully!',
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    protected function duplicateFolder(FolderFactory $sourceFolder, int $parentId, int $userId)
    {
        $newFolder = FolderFactory::create([
            'parent_id' => $parentId,
            'icon_id' => $sourceFolder->icon_id,
            'user_id' => $userId,
            'name' => $sourceFolder->name . ' Copy',
            'slug' => Str::slug($sourceFolder->name . '-copy-' . Str::random(5)),
            'is_favourite' => 0,
        ]);

        $files = File::where('fileable_type', FolderFactory::class)
            ->where('fileable_id', $sourceFolder->id)
            ->get();

        foreach ($files as $file) {

            $extension = pathinfo($file->file_path, PATHINFO_EXTENSION);

            $newPath = 'uploads/' . $userId . '/' . Str::uuid() . '.' . $extension;

            Storage::copy($file->file_path, $newPath);

            File::create([
                'user_id'       => $userId,
                'file_path'     => $newPath,
                'file_name'     => $file->file_name,
                'mime_type'     => $file->mime_type,
                'file_size'     => $file->file_size,
                'is_temp'       => 0,
                'fileable_type' => FolderFactory::class,
                'fileable_id'   => $newFolder->id,
                'is_favourite'  => 0,
            ]);
        }

        $children = FolderFactory::where('parent_id', $sourceFolder->id)->get();

        foreach ($children as $child) {
            $this->duplicateFolder($child, $newFolder->id, $userId);
        }

        return $newFolder;
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

        $totalFiles = $totalFilesAndSize?->total_files ?? 0;
        $totalSize = $this->fileService->getFormattedSize($totalFilesAndSize?->total_size ?? 0);

        return view('user.orbit_zone.folder_factory.folder_factory_list', compact('icons', 'folderFactories', 'totalFiles', 'totalSize'));
    }

    public function showFiles(User $user, $folderId, Request $request)
    {
        $folderFactory = FolderFactory::where('id', $folderId)->where('user_id', $user->id)->first();
        if (!$folderFactory) {
            abort(404, 'Folder not exists!');
        }

        $foldersQuery = FolderFactory::query()
            ->select([
                'id',
                'name as item_name',
                DB::raw("'folder' as item_type"),
                DB::raw('NULL as mime_type'),
                DB::raw('NULL as file_size'),
                DB::raw('NULL as file_path'),
                DB::raw('NULL as file_name'),
                'icon_id',
                'is_favourite',
                'created_at',
                'updated_at',
            ])
            ->where('user_id', $user->id)
            ->where('parent_id', $folderFactory->id);

        $filesQuery = File::query()
            ->select([
                'id',
                'file_name as item_name',
                DB::raw("'file' as item_type"),
                'mime_type',
                'file_size',
                'file_path',
                'file_name',
                DB::raw('NULL as icon_id'),
                'is_favourite',
                'created_at',
                'updated_at',
            ])
            ->where('user_id', $user->id)
            ->where('fileable_type', FolderFactory::class)
            ->where('fileable_id', $folderId);

        $query = DB::query()
            ->fromSub(
                $foldersQuery->clone()->unionAll($filesQuery->clone()),
                'items'
            );
        $items = $query->paginate(50, ['*'], 'items_page')
            ->withQueryString();

        $items->getCollection()->transform(function ($item) {
            return $this->bindFileData($item);
        });

        $icons = Icon::where('category', 'folder')->where('status', 'active')->orderBy('order', 'asc')->get();
        $folderFactories = FolderFactory::where('user_id', $user->id)->get();

        $breadcrumbs = collect();

        $current = $folderFactory;

        while ($current) {
            $breadcrumbs->prepend($current);
            $current = $current->parent;
        }

        return view('user.orbit_zone.folder_factory.folder_factory_files_list', compact('folderFactory', 'items', 'breadcrumbs', 'icons', 'folderFactories'));
    }

    public function create(User $user)
    {

        FolderFactory::firstOrCreate([
            'user_id' => $user->id,
            'name' => 'My Drive',
            'slug' => 'my-drive'
        ]);

        $folderFactories = FolderFactory::where('user_id', $user->id)
            ->orderByRaw("
                CASE
                    WHEN slug = ? AND parent_id IS NULL THEN 0
                    ELSE 1
                END
            ", ['my-drive'])
            ->orderBy('name', 'asc')
            ->get();

        return view('user.orbit_zone.folder_factory.file_upload_form', compact('folderFactories'));
    }

    public function store(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'max:255',
                'string',
                Rule::unique('folder_factories')->where(function ($query) use ($user) {
                    return $query->where('user_id', $user->id);
                }),
            ],
            'icon' => 'nullable|integer|exists:icons,id',
            'folder_id' => 'nullable|exists:folder_factories,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'field' => $validator->errors()->keys()[0],
                'message' => $validator->errors()->first()
            ], 422);
        }

        if ($request->has('folder_id')) {

            if (FolderFactory::where('user_id', $user->id)->where('id', $request->folder_id)->exists()) {
                $parentId = $request->folder_id;
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => "Unauthorised access!"
                ], 403);
            }
        } else {
            $parentId = FolderFactory::where('user_id', $user->id)->whereNull('parent_id')->value('id');
        }

        if (!$parentId) {
            $folderFactory = FolderFactory::firstOrCreate([
                'user_id' => $user->id,
                'name' => 'My Drive',
                'slug' => 'my-drive'
            ]);
            $parentId = $folderFactory->id;
        }

        $folderFactory = FolderFactory::create([
            'name' => $request->name,
            'user_id' => $user->id,
            'icon_id' => $request->icon ?? null,
            'parent_id' => $parentId,
            'slug' => Str::slug($request->name)
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Folder Created Successfully!',
            'folderFactory' => $folderFactory,
        ]);
    }

    public function update(User $user, FolderFactory $folderFactory, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'max:255',
                'string',
                Rule::unique('folder_factories')
                    ->ignore($folderFactory->id)
                    ->where(function ($query) use ($user) {
                        return $query->where('user_id', $user->id);
                    }),
            ],
            'icon' => 'nullable|integer|exists:icons,id',
            'folder_id' => 'nullable|exists:folder_factories,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'field' => $validator->errors()->keys()[0],
                'message' => $validator->errors()->first()
            ], 422);
        }

        Gate::authorize('update', $folderFactory);

        $folderFactory->name = $request->name;
        $folderFactory->icon_id = $request->icon;
        $folderFactory->slug =  Str::slug($request->name);
        $folderFactory->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Folder Updated Successfully!',
            'folderFactory' => $folderFactory,
        ]);
    }


    public function destroy(User $user, FolderFactory $folderFactory, Request $request)
    {
        Gate::authorize('delete', $folderFactory);

        $folderFactory->delete();

        if ($request->isJson() || $request->wantsJson()) {
            return response()->json([
                'status' => 200,
                'message' => 'Folder factory destroyed successfully!'
            ]);
        }
        return redirect()->back()->with('success', 'Folder factory destroyed successfully!');
    }

    public function destroyAll(User $user, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'folder_ids'   => 'nullable|array',
            'folder_ids.*' => 'exists:folder_factories,id',
            'file_ids'     => 'nullable|array',
            'file_ids.*'   => 'exists:files,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::transaction(function () use ($request) {

            if (!empty($request->folder_ids)) {
                $folders = FolderFactory::whereIn('id', $request->folder_ids)->get();

                foreach ($folders as $folder) {
                    Gate::authorize('delete', $folder);
                    $folder->delete();
                }
            }

            if (!empty($request->file_ids)) {
                $files = File::whereIn('id', $request->file_ids)->get();

                foreach ($files as $file) {
                    Gate::authorize('delete', $file);
                    $file->delete();
                }
            }
        });

        if ($request->isJson() || $request->wantsJson()) {
            return response()->json([
                'status' => 200,
                'message' => 'Selected items deleted successfully!',
            ]);
        }

        return redirect()->back()->with(
            'success',
            'Selected items deleted successfully!'
        );
    }

    public function restoreAll(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'folder_ids'   => 'nullable|array',
            'folder_ids.*' => 'exists:folder_factories,id',
            'file_ids'     => 'nullable|array',
            'file_ids.*'   => 'exists:files,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::transaction(function () use ($request) {

            FolderFactory::onlyTrashed()
                ->whereIn('id', $request->folder_ids ?? [])
                ->get()
                ->each(function ($folder) {
                    Gate::authorize('restore', $folder);
                    $folder->restore();
                });

            File::onlyTrashed()
                ->whereIn('id', $request->file_ids ?? [])
                ->get()
                ->each(function ($file) {
                    Gate::authorize('restore', $file);
                    $file->restore();
                });
        });

        return response()->json([
            'status' => 200,
            'message' => 'Selected items restored successfully!',
        ]);
    }

    public function destroyAllPermanent(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'folder_ids'   => 'nullable|array',
            'folder_ids.*' => 'exists:folder_factories,id',
            'file_ids'     => 'nullable|array',
            'file_ids.*'   => 'exists:files,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::transaction(function () use ($request) {

            // Permanently delete folders
            if (!empty($request->folder_ids)) {
                $folders = FolderFactory::onlyTrashed()
                    ->whereIn('id', $request->folder_ids)
                    ->get();

                foreach ($folders as $folder) {
                    Gate::authorize('forceDelete', $folder);

                    $this->permanentlyDeleteFolder($folder);
                }
            }

            // Permanently delete individual files
            if (!empty($request->file_ids)) {
                $files = File::onlyTrashed()
                    ->whereIn('id', $request->file_ids)
                    ->get();

                foreach ($files as $file) {
                    Gate::authorize('forceDelete', $file);

                    $this->fileService->deleteIfExists($file->file_path);

                    $file->forceDelete();
                }
            }
        });

        if ($request->isJson() || $request->wantsJson()) {
            return response()->json([
                'status'  => 200,
                'message' => 'Selected items permanently deleted successfully!',
            ]);
        }

        return redirect()->back()->with(
            'success',
            'Selected items permanently deleted successfully!'
        );
    }


    /**
     * Recursively permanently delete a folder and its contents.
     */
    private function permanentlyDeleteFolder(FolderFactory $folder): void
    {
        // Delete child folders first
        $childFolders = FolderFactory::withTrashed()
            ->where('parent_id', $folder->id)
            ->get();

        foreach ($childFolders as $childFolder) {
            $this->permanentlyDeleteFolder($childFolder);
        }

        // Delete files in folder
        $files = File::withTrashed()
            ->where('fileable_type', FolderFactory::class)
            ->where('fileable_id', $folder->id)
            ->get();

        foreach ($files as $file) {
            $this->fileService->deleteIfExistsOnDrive($file->file_path, 'private');

            $file->forceDelete();
        }

        // Delete folder itself
        $folder->forceDelete();
    }

    /* ================================ BEGIN - CHUNK UPLOAD ================================*/


    public function uploadStatus(Request $request)
    {
        $uploadId = $request->query('uploadId');
        if (!$uploadId) {
            return response()->json(['error' => 'Invalid upload ID'], 400);
        }

        $disk = Storage::disk('private');
        $tempDir = $disk->path('files/temp/' . $uploadId);

        if (!file_exists($tempDir)) {
            return response()->json(['uploadedChunks' => []]);
        }

        $uploadedChunks = array_diff(scandir($tempDir), ['.', '..']);
        $uploadedChunks = array_map('intval', $uploadedChunks);
        sort($uploadedChunks);
        return response()->json(['uploadedChunks' => $uploadedChunks]);
    }

    public function uploadChunk(User $user, Request $request)
    {
        $fileNameRaw = $request->get('fileName');
        $chunkIndexRaw = $request->get('chunkIndex');
        $totalChunksRaw = $request->get('totalChunks');
        $folderIdRaw = $request->get('folderId');
        $uploadId = $request->get('uploadId');

        if (!$uploadId) {
            return response()->json(['error' => 'Missing uploadId'], 400);
        }

        if (!$fileNameRaw) {
            return response()->json(['error' => 'Missing fileName'], 400);
        }

        if ($chunkIndexRaw === null || $chunkIndexRaw === '') {
            return response()->json(['error' => 'Missing chunkIndex'], 400);
        }

        if ($totalChunksRaw === null || $totalChunksRaw === '') {
            return response()->json(['error' => 'Missing totalChunks'], 400);
        }

        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'Missing file payload'], 400);
        }

        $chunkIndex = is_numeric($chunkIndexRaw) ? (int)$chunkIndexRaw : null;
        $totalChunks = is_numeric($totalChunksRaw) ? (int)$totalChunksRaw : 0;
        $fileName = (string)$fileNameRaw;
        $folderId = $folderIdRaw ? (int)$folderIdRaw : null;

        if (!is_int($chunkIndex) || $chunkIndex < 0) {
            return response()->json(['error' => 'Invalid chunkIndex'], 400);
        }

        if ($totalChunks <= 0) {
            return response()->json(['error' => 'Invalid totalChunks'], 400);
        }

        $disk = Storage::disk('private');
        $tempDir = $disk->path('files/temp/' . $uploadId);

        $folderCacheKey   = "upload_folder_{$user->id}_{$uploadId}";
        $progressCacheKey = "upload_progress_{$user->id}_{$uploadId}";

        if ($chunkIndex === 0) {
            if (!$folderId) {
                return response()->json(['error' => 'Missing folderId for initial chunk'], 400);
            }

            $folder = FolderFactory::where('id', $folderId)->where('user_id', $user->id)->first();
            if (!$folder) {
                return response()->json(['error' => 'Folder not found or not allowed'], 403);
            }

            Cache::put($folderCacheKey, $folder->id, 3600);
            Cache::put($progressCacheKey, 0, 3600);

            if (!file_exists($tempDir)) {
                if (!@mkdir($tempDir, 0777, true) && !is_dir($tempDir)) {
                    return response()->json(['error' => 'Server error creating temp dir'], 500);
                }
            }
        } else {
            $cachedFolderId = Cache::get($folderCacheKey);
            if (!$cachedFolderId) {
                return response()->json(['error' => 'Upload session expired or not initialized (missing chunk 0)'], 400);
            }
            $folderId = $cachedFolderId;
        }

        try {
            $uploaded = $request->file('file')->move($tempDir, (string)$chunkIndex);
            if ($uploaded === false) {
                return response()->json(['error' => 'Failed to save chunk'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save chunk', 'details' => $e->getMessage()], 500);
        }

        if (!Cache::has($progressCacheKey)) {
            Cache::put($progressCacheKey, 1, 3600);
        } else {
            Cache::increment($progressCacheKey);
        }

        $currentProgress = Cache::get($progressCacheKey, 0);

        if ($currentProgress >= $totalChunks) {
            $finalFileName = 'F-' . Str::uuid() . '-' . $fileName;
            $finalPath = $disk->path('files/' . $finalFileName);

            try {
                $finalFile = fopen($finalPath, 'w');
                for ($i = 0; $i < $totalChunks; $i++) {
                    $chunkPath = $tempDir . '/' . $i;
                    if (!file_exists($chunkPath)) {
                        fclose($finalFile);
                        return response()->json(['error' => "Missing chunk {$i} during merge"], 500);
                    }
                    $chunk = fopen($chunkPath, 'r');
                    while ($data = fread($chunk, 1024 * 1024)) {
                        fwrite($finalFile, $data);
                    }
                    fclose($chunk);
                    @unlink($chunkPath);
                }
                fclose($finalFile);
                @rmdir($tempDir);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to merge chunks', 'details' => $e->getMessage()], 500);
            }

            $mimeType = $this->fileService->getFileMimeTypeByPath($finalPath);

            File::create([
                'user_id'       => $user->id,
                'file_path'     => 'files/' . $finalFileName,
                'file_name'     => $fileName,
                'mime_type'     => $mimeType,
                'file_size'     => $disk->size('files/' . $finalFileName) ?? null,
                'fileable_type' => FolderFactory::class,
                'fileable_id'   => $folderId,
            ]);

            Cache::forget($folderCacheKey);
            Cache::forget($progressCacheKey);

            return response()->json(['message' => 'Upload complete']);
        }

        return response()->json(['message' => 'Chunk uploaded']);
    }


    /* ================================ END - CHUNK UPLOAD ================================ */
}
