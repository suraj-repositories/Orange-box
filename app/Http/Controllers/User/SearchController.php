<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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



            /*
                [
                    'icon' , 'heading' , 'search_para', 'link'
                ]

                find within
                - pages
                - digestion
                - think pad
                - syntax store
                - folder factory
                - projects - module - task

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
