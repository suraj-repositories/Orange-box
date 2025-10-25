<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\User;
use App\Services\FileService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class EducationController extends Controller
{
    public function __construct(public FileService $fileService) {}

    public function store(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'institution' => 'required|string|max:256',
            'institution_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'degree' => 'required|string|max:100',
            'field_of_study' => 'required|string|max:256',
            'grade' => 'nullable|numeric|min:0|max:100',
            'start_date' => [
                'required',
                'date',
                function ($_, $value, $fail) use ($request) {
                    if ($request->filled('end_date') && $value > $request->end_date) {
                        $fail('The start date must be before or equal to the end date.');
                    }
                },
            ],
            'end_date' => [
                'required',
                'date',
                function ($_, $value, $fail) use ($request) {
                    if ($request->filled('start_date') && $value < $request->start_date) {
                        $fail('The end date must be after or equal to the start date.');
                    }
                },
            ],
            'description' => 'nullable|string|max:500'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'field' => $validator->errors()->keys()[0],
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $logoPath = null;
            if ($request->has('institution_logo')) {
                $logoPath = $this->fileService->uploadFile($request->institution_logo, 'institution_logo');
            }

            $edu = Education::create([
                'user_id' => $user->id,
                'institution' => $request->input('institution') ?? "",
                'institution_logo' => $logoPath,
                'degree' => $request->input('degree') ?? "",
                'field_of_study' => $request->input('field_of_study') ?? '',
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'grade' => $request->input('grade'),
                'description' => $request->description,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => "Education added successfully!"
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage()
            ], 500);
        }
    }

    public function update(User $user, Education $education, Request $request)
    {
        Gate::authorize('update', $education);

        $validator = Validator::make($request->all(), [
            'institution' => 'required|string|max:256',
            'institution_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'degree' => 'required|string|max:100',
            'field_of_study' => 'required|string|max:256',
            'grade' => 'nullable|string|max:10',
            'start_date' => [
                'required',
                'date',
                function ($_, $value, $fail) use ($request) {
                    if ($request->filled('end_date') && $value > $request->end_date) {
                        $fail('The start date must be before or equal to the end date.');
                    }
                },
            ],
            'end_date' => [
                'required',
                'date',
                function ($_, $value, $fail) use ($request) {
                    if ($request->filled('start_date') && $value < $request->start_date) {
                        $fail('The end date must be after or equal to the start date.');
                    }
                },
            ],
            'description' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'field' => $validator->errors()->keys()[0],
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            if ($request->has('institution_logo')) {
                $this->fileService->deleteIfExists($education->institution_logo);
                $logoPath = $this->fileService->uploadFile($request->institution_logo, 'institution_logo');
                $education->institution_logo = $logoPath;
            }

            $education->user_id = $user->id;
            $education->institution = $request->input('institution') ?? "";
            $education->degree = $request->input('degree') ?? "";
            $education->field_of_study = $request->input('field_of_study') ?? '';
            $education->start_date = $request->input('start_date');
            $education->end_date = $request->input('currently_working') == "yes" ? null : $request->input('end_date');
            $education->grade = $request->input('grade');
            $education->description = $request->description;
            $education->updated_at = now();
            $education->save();

            return response()->json([
                'status' => 'success',
                'message' => "Education updated successfully!"
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage()
            ], 500);
        }
    }

    public function destroy(User $user, Education $education, Request $request)
    {
        Gate::authorize('delete', $education);
        $education->delete();
        return response()->json([
            'status' => 'success',
            'message' => "Education deleted successfully!"
        ]);
    }
}
