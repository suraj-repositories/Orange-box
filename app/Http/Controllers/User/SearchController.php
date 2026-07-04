<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DailyDigest;
use App\Models\File;
use App\Models\FolderFactory;
use App\Models\ProjectBoard;
use App\Models\ProjectModule;
use App\Models\SyntaxStore;
use App\Models\Task;
use App\Models\ThinkPad;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class SearchController extends Controller
{
    public function __construct(public FileService $fileService) {}

    //
    public function search(Request $request, User $user)
    {

        try {
            $query = trim($request->q ?? "");

            if ($query === "") {
                return response()->json([
                    'success' => false,
                    'message' => 'Query too short',
                    'html' => ''
                ]);
            }

            $digestions = DailyDigest::query()
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('sub_title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                })
                ->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                        ->orWhere(function ($q) use ($user) {
                            $q->where('user_id', '!=', $user->id)
                                ->where('visibility', 'public');
                        });
                })
                ->withCount('likes')
                ->orderByDesc('likes_count')
                ->take(10)
                ->get();

            $thinkPads = ThinkPad::query()
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('sub_title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                })
                ->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                        ->orWhere(function ($q) use ($user) {
                            $q->where('user_id', '!=', $user->id)
                                ->where('visibility', 'public');
                        });
                })
                ->withCount('likes')
                ->orderByDesc('likes_count')
                ->take(10)
                ->get();

            $syntaxStore = SyntaxStore::query()
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('preview_text', 'like', "%{$query}%")
                        ->orWhere('content', 'like', "%{$query}%");
                })
                ->where(function ($q) use ($user) {
                    $q->where(function ($owner) use ($user) {
                        $owner->where('user_id', $user->id)
                            ->whereIn('status', ['publish', 'draft', 'archived']);
                    })
                        ->orWhere(function ($public) use ($user) {
                            $public->where('user_id', '!=', $user->id)
                                ->where('status', 'publish');
                        });
                })
                ->withCount('likes')
                ->orderByDesc('likes_count')
                ->take(10)
                ->get();

            $folderFactory = FolderFactory::where('user_id', $user->id)
                ->where('name', 'like', "%{$query}%")
                ->take(10)
                ->get();

            $files = File::where('user_id', $user->id)
                ->where('fileable_type', FolderFactory::class)
                ->where('file_name', 'like', "%$query%")
                ->take(10)
                ->get();


            $projects = ProjectBoard::query()
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('preview_text', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                })
                ->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                        ->orWhereHas('users', function ($q) use ($user) {
                            $q->where('users.id', $user->id);
                        });
                })
                ->take(10)
                ->get();

            $modules = ProjectModule::query()
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                })
                ->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                        ->orWhereHas('assignees', function ($q) use ($user) {
                            $q->where('users.id', $user->id);
                        });
                })
                ->take(10)
                ->get();

            $tasks = Task::where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
                ->where(function ($q) use ($user) {
                    $q->where('assigned_to', $user->id)
                        ->orWhere('user_id', $user->id);
                })
                ->orderByDesc('id')
                ->take(10)
                ->get();

            $pagesArray = $this->searchPages($this->pagesArray() ?? [], $query);
            $digestionArray = $this->digestionToArray($digestions, $query);
            $thinkPadsArray = $this->thinkPadToArray($thinkPads, $query);
            $syntaxStoreArray = $this->syntaxStoreToArray($syntaxStore, $query);
            $folderFactoryArray  = $this->folderFactoryToArray($folderFactory, $query);
            $filesArray  = $this->filesToArray($files, $query);
            $projectsArray = $this->projectsToArray($projects, $query);
            $modulesArray = $this->projectModulesToArray($modules, $query);
            $tasksArray = $this->tasksToArray($tasks, $query);

            $structured = [
                'Pages' => $pagesArray,
                'Digestions' => $digestionArray,
                'Think Pads' => $thinkPadsArray,
                'Syntax Store' => $syntaxStoreArray,
                'Folder Factory' => array_merge($folderFactoryArray, $filesArray),
                'Project Board' => $projectsArray,
                'Modules' => $modulesArray,
                'Tasks' => $tasksArray
            ];

            return response()->json([
                'success' => true,
                'message' => 'Result obtained successfully',
                'html' => view('components.user.search-results', [
                    'groups' => $structured,
                    'query' => $query
                ])->render()
            ]);
        } catch (Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => $th->getMessage()
            ]);
        }
    }

    public function searchPages(array $pages, string $query): array
    {
        $query = strtolower(trim($query));

        if ($query === '') {
            return $pages;
        }

        return array_values(array_filter($pages, function ($page) use ($query) {
            return str_contains(strtolower($page['heading']), $query)
                || str_contains(strip_tags(strtolower($page['search_para'])), $query);
        }));
    }

    public function pagesArray()
    {
        return [
            [
                'icon' => 'bi bi-house',
                'heading' => 'Dashboard',
                'search_para' => '',
                'link' => authRoute('user.dashboard'),
            ],
            [
                'icon' => 'bi bi-file-earmark-text',
                'heading' => 'Daily Digest',
                'search_para' => '',
                'link' => authRoute('user.daily-digest'),
            ],
            [
                'icon' => 'bi bi-clipboard2',
                'heading' => 'Think Pad',
                'search_para' => '',
                'link' => authRoute('user.think-pad'),
            ],
            [
                'icon' => 'bi bi-code-slash',
                'heading' => 'Syntax Store',
                'search_para' => '',
                'link' => authRoute('user.syntax-store'),
            ],
            [
                'icon' => 'bi bi-file-richtext',
                'heading' => 'Documentations',
                'search_para' => '',
                'link' => authRoute('user.documentation.index'),
            ],
            [
                'icon' => 'bi bi-folder',
                'heading' => 'Folder Factory',
                'search_para' => '',
                'link' => authRoute('user.file-manager'),
            ],
            [
                'icon' => 'bi bi-kanban',
                'heading' => 'Project Board',
                'search_para' => '',
                'link' => authRoute('user.project-board'),
            ],
            [
                'icon' => 'bi bi-box',
                'heading' => 'Project Module',
                'search_para' => '',
                'link' => authRoute('user.modules.index'),
            ],
            [
                'icon' => 'bi bi-check2-square',
                'heading' => 'Project Tasks',
                'search_para' => '',
                'link' => authRoute('user.tasks.index'),
            ],
            [
                'icon' => 'bi bi-people',
                'heading' => 'Collaboration',
                'search_para' => '',
                'link' => authRoute('user.collab.all.project-board.index'),
            ],
            [
                'icon' => 'bi bi-person',
                'heading' => 'My Profile',
                'search_para' => '',
                'link' => authRoute('user.profile.index'),
            ],
            [
                'icon' => 'bi bi-lock',
                'heading' => 'Password Locker',
                'search_para' => '',
                'link' => authRoute('user.password_locker.index'),
            ],
            [
                'icon' => 'bi bi-gear',
                'heading' => 'Settings',
                'search_para' => '',
                'link' => authRoute('user.settings.index'),
            ],
        ];
    }



    public function digestionToArray($digestions, $search)
    {
        $result = [];
        foreach ($digestions as $digestion) {
            $result[] = [
                'icon' => 'bi bi-file-earmark-text',
                'heading' => $this->highlight($digestion->title, $search),
                'search_para' => $this->searchSnippet($digestion->sub_title . ' ' . $digestion->description, $search),
                'link' => authRoute('user.daily-digest.show', ['dailyDigest' => $digestion])
            ];
        }
        return $result;
    }

    public function thinkPadToArray($thinkPads, $search)
    {
        $result = [];
        foreach ($thinkPads as $thinkPad) {
            $result[] = [
                'icon' => 'bi bi-clipboard2',
                'heading' => $this->highlight($thinkPad->title, $search),
                'search_para' => $this->searchSnippet($thinkPad->sub_title . ' ' . $thinkPad->description, $search),
                'link' => authRoute('user.think-pad.show', ['thinkPad' => $thinkPad])
            ];
        }
        return $result;
    }

    public function syntaxStoreToArray($syntaxStore, $search)
    {
        $result = [];
        foreach ($syntaxStore as $syntax) {
            $result[] = [
                'icon' => 'bi bi-exclamation-octagon',
                'heading' => $this->highlight($syntax->title, $search),
                'search_para' => $this->searchSnippet($syntax->content, $search),
                'link' => authRoute('user.syntax-store.show', ['syntaxStore' => $syntax])
            ];
        }
        return $result;
    }

    public function folderFactoryToArray($folders, $search)
    {
        $result = [];
        foreach ($folders as $folder) {
            $result[] = [
                'icon' => 'bi bi-folder',
                'heading' => $this->highlight($folder->name, $search),
                'search_para' => '',
                'link' => authRoute('user.folder-factory.files.index', ['folderId' => $folder->id])
            ];
        }
        return $result;
    }

    public function filesToArray($files, $search)
    {
        $result = [];
        foreach ($files as $file) {
            $icon  = $this->fileService->getIconFromExtension(pathinfo($file->file_path, PATHINFO_EXTENSION) ?? null);
            $result[] = [
                'icon' => $icon ?? 'bi bi-file-earmark',
                'heading' => $this->highlight($file->file_name, $search),
                'search_para' => $this->highlight($file->mime_type, $search),
                'link' => authRoute('user.folder-factory.files.index', ['folderId' => $file->fileable_id])
            ];
        }
        return $result;
    }

    public function projectsToArray($projects, $search)
    {
        $result = [];
        foreach ($projects as $project) {
            $result[] = [
                'icon' => 'bx bx-cube-alt',
                'heading' => $this->highlight($project->title, $search),
                'search_para' => $this->searchSnippet($project->description, $search),
                'link' => $project->user_id == Auth::id() ?
                    authRoute('user.project-board.show', ['slug' => $project->slug])
                    : authRoute('user.collab.project-board.show', ['slug' => $project->slug])
            ];
        }
        return $result;
    }

    public function projectModulesToArray($modules, $search)
    {
        $result = [];
        foreach ($modules as $module) {
            $result[] = [
                'icon' => 'bx bxs-cube-alt',
                'heading' => $this->highlight($module->name, $search),
                'search_para' => $this->searchSnippet($module->description, $search),
                'link' => $module->user_id == Auth::id() ?
                    authRoute('user.project-board.modules.show', ['slug' => $module->slug])
                    : authRoute('user.collab.modules.show', ['slug' => $module->projectBoard->slug, 'module' => $module])
            ];
        }
        return $result;
    }

    public function tasksToArray($tasks, $search)
    {
        $result = [];
        foreach ($tasks as $task) {
            $result[] = [
                'icon' => 'bx bx-target-lock',
                'heading' => $this->highlight($task->name, $search),
                'search_para' => $this->searchSnippet($task->description, $search),
                'link' => $task->user_id == Auth::id() ?
                    authRoute('user.tasks.show', ['task' => $task])
                    : authRoute('user.collab.tasks.show', ['task' => $task])
            ];
        }
        return $result;
    }



    function highlight($text, $query)
    {
        return preg_replace("/($query)/i", '<mark>$1</mark>', $this->normalizeText($text));
    }

    function normalizeText($content)
    {
        $text = strip_tags($content);

        // Step 2: Remove Markdown syntax
        $text = preg_replace('/(```.*?```)/s', ' ', $text);
        $text = preg_replace('/`.*?`/', ' ', $text);
        $text = preg_replace('/!\[.*?\]\(.*?\)/', ' ', $text);
        $text = preg_replace('/\[(.*?)\]\(.*?\)/', '$1', $text);
        $text = preg_replace('/[#>*_\-~]/', ' ', $text);

        // Normalize spaces
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        return $text;
    }

    function searchSnippet($content, $query, $limit = 80)
    {
        $text = $this->normalizeText($content);

        $length = strlen($text);

        $lowerText = strtolower($text);
        $lowerQuery = strtolower($query);

        $pos = strpos($lowerText, $lowerQuery);

        if ($pos !== false) {
            $start = max($pos - $limit / 2, 0);
            $snippet = substr($text, $start, $limit);

            // Clean broken words BEFORE adding dots
            $snippet = preg_replace('/^\S*\s/', '', $snippet);
            $snippet = preg_replace('/\s\S*$/', '', $snippet);

            if ($start > 0) {
                $snippet = '...' . $snippet;
            }

            if ($start + $limit < $length) {
                $snippet .= '...';
            }
        } else {
            $snippet = substr($text, 0, $limit);

            $snippet = preg_replace('/\s\S*$/', '', $snippet);

            if ($limit < $length) {
                $snippet .= '...';
            }
        }

        $snippet = preg_replace('/' . preg_quote($query, '/') . '/i', '<mark>$0</mark>', $snippet);

        return $snippet;
    }
}
