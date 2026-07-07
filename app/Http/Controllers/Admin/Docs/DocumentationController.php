<?php

namespace App\Http\Controllers\Admin\Docs;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    //

    public function index(Request $request)
    {
        $hasFilter = collect(['search', 'from_date', 'to_date'])
            ->some(fn($field) => $request->filled($field));

        $documentations = Documentation::with('user', 'latestRelease')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($user) use ($search) {
                            $user->where('username', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhereHas('details', function ($details) use ($search) {
                                    $details->whereRaw(
                                        "CONCAT(first_name, ' ', last_name) LIKE ?",
                                        ["%{$search}%"]
                                    )
                                        ->orWhere('first_name', 'like', "%{$search}%")
                                        ->orWhere('last_name', 'like', "%{$search}%")
                                        ->orWhere('email', 'like', "%{$search}%")
                                        ->orWhere('contact', 'like', "%{$search}%");
                                });
                        });
                });
            })
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereDate('purchased_at', '>=', $request->from_date);
            })
            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereDate('purchased_at', '<=', $request->to_date);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.docs.documentation.documentation-list', [
            'title' => 'Documenations',
            'documentations' => $documentations,
            'hasFilter' => $hasFilter
        ]);
    }
}
