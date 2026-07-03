<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DailyDigest;
use App\Models\FolderFactory;
use App\Models\ProjectBoard;
use App\Models\ProjectModule;
use App\Models\SyntaxStore;
use App\Models\Task;
use App\Models\ThinkPad;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

class SearchController extends Controller
{
    //
    public function search(Request $request, User $user)
    {

        try {
            $query = trim($request->q ?? "");

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


            /*
                [
                    'icon' , 'heading' , 'search_para', 'link'
                ]

                find within
                - pages
                - digestion --- done
                - think pad --- done
                - syntax store --- done
                - folder factory  --- done
                - projects - module - task -- done

            */


            return response()->json([
                'success' => true,
                'message' => 'Result obtained successfully',
                'html' => "<p>result obtained</p>"
            ]);

            // if ($query === "") {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Query too short',
            //         'html' => ''
            //     ]);
            // }

            // $documentation = Documentation::where('url', $slug)->firstOrFail();

            // $releaseId = null;

            // if ($version) {
            //     $release = DocumentationRelease::where('version', $version)
            //         ->where('documentation_id', $documentation->id)
            //         ->first();

            //     $releaseId = $release?->id;
            // }

            // $results = DocumentationSection::search($query)
            //     ->query(function ($q) use ($documentation, $releaseId) {
            //         $q->whereHas('page', function ($q2) use ($documentation, $releaseId) {
            //             $q2->where('documentation_id', $documentation->id);

            //             if ($releaseId) {
            //                 $q2->where('release_id', $releaseId);
            //             }
            //         });
            //     })
            //     ->take(20)
            //     ->get()
            //     ->load('page.parent', 'page.documentation', 'page.documentationRelease', 'page.user');


            // $structured = $results->groupBy(function ($section) {
            //     return optional($section->page->parent)->title
            //         ?? $section->page->title
            //         ?? 'General';
            // });

            // return response()->json([
            //     'success' => true,
            //     'message' => 'Result obtained successfully',
            //     'html' => view('components.search.results', [
            //         'groups' => $structured,
            //         'query' => $query
            //     ])->render()
            // ]);
        } catch (Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!',
                'error' => $th->getMessage()
            ]);
        }
    }
}
