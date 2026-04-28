<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Models\DocumentationPage;
use App\Models\DocumentationPageView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserActivityController extends Controller
{
    //
    public function pageExit(Request $request)
    {
        Log::info('here - '. $request->view_id);
        $isUpdated = DocumentationPageView::where('id', $request->view_id)
            ->whereNull('left_at')
            ->update([
                'left_at' => now(),
                'duration' => $request->duration
            ]);

            Log::info('updated - '.  $isUpdated);
    }
}
