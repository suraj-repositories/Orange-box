<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\DocumentationPageFeedback;

class DocumentationFeedbackController extends Controller
{
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'documentation_page_id' => 'required|exists:documentation_pages,id',
            'rating' => 'required|integer|min:1|max:4',
            'feedback' => 'nullable|string|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $user = auth()->user();
            $ip = $request->ip();

            $existing = DocumentationPageFeedback::where('documentation_page_id', $request->documentation_page_id)
                ->where(function ($q) use ($user, $ip) {
                    if ($user) {
                        $q->where('user_id', $user->id);
                    } else {
                        $q->whereNull('user_id')
                            ->where('ip_address', $ip);
                    }
                })
                ->first();

            if ($existing) {
                $existing->update([
                    'rating' => $request->rating,
                    'feedback' => $request->feedback,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Feedback updated successfully',
                    'data' => $existing
                ]);
            }

            $feedback = DocumentationPageFeedback::create([
                'uuid' => (string) Str::uuid(),
                'documentation_page_id' => $request->documentation_page_id,
                'user_id' => $user ? $user->id : null,
                'rating' => $request->rating,
                'feedback' => $request->feedback,
                'ip_address' => $user ? null : $ip,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Feedback submitted successfully',
                'data' => $feedback
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
