<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkExperience;
use App\Services\FileService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class WorkExperienceController extends Controller
{

    public function __construct(public FileService $fileService) {}

    //
    public function store(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_title' => 'required|string|max:256',
            'employment_type' => 'required|in:full_time,part_time,internship,freelance',
            'company' => 'required|string|max:256',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'location' => 'required|string|max:256',
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
                'nullable',
                'date',
                function ($_, $value, $fail) use ($request) {
                    if ($request->filled('start_date') && $value < $request->start_date) {
                        $fail('The end date must be after or equal to the start date.');
                    }
                },
            ],
            'currently_working' => 'nullable|string',
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
            if ($request->has('company_logo')) {
                $logoPath = $this->fileService->uploadFile($request->company_logo, 'company_logo');
            }

            $exp = WorkExperience::create([
                'user_id' => $user->id,
                'job_title' => $request->input('job_title') ?? "",
                'company' => $request->input('company') ?? "",
                'company_logo' => $logoPath,
                'location' => $request->input('location') ?? '',
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'currently_working' => $request->input('currently_working') == "yes",
                'employment_type' => $request->input('employment_type'),
                'description' => $request->description,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => "Work experience created successfully!"
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage()
            ], 500);
        }
    }

    public function update(User $user, WorkExperience $workExperience, Request $request)
    {
        Gate::authorize('update', $workExperience);

        $validator = Validator::make($request->all(), [
            'job_title' => 'required|string|max:256',
            'employment_type' => 'required|in:full_time,part_time,internship,freelance',
            'company' => 'required|string|max:256',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'location' => 'required|string|max:256',
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
                'nullable',
                'date',
                function ($_, $value, $fail) use ($request) {
                    if ($request->filled('start_date') && $value < $request->start_date) {
                        $fail('The end date must be after or equal to the start date.');
                    }
                },
            ],
            'currently_working' => 'nullable|string',
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
            if ($request->has('company_logo')) {
                $this->fileService->deleteIfExists($workExperience->company_logo);
                $logoPath = $this->fileService->uploadFile($request->company_logo, 'company_logo');
                $workExperience->company_logo = $logoPath;
            }

            $workExperience->user_id = $user->id;
            $workExperience->job_title = $request->input('job_title') ?? "";
            $workExperience->company = $request->input('company') ?? "";
            $workExperience->location = $request->input('location') ?? '';
            $workExperience->start_date = $request->input('start_date');
            $workExperience->end_date = $request->input('currently_working') == "yes" ? null : $request->input('end_date');
            $workExperience->currently_working = $request->input('currently_working') == "yes";
            $workExperience->employment_type = $request->input('employment_type');
            $workExperience->description = $request->description;
            $workExperience->updated_at = now();
            $workExperience->save();

            return response()->json([
                'status' => 'success',
                'message' => "Work experience updated successfully!"
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage()
            ], 500);
        }
    }

    public function destroy(User $user, WorkExperience $workExperience, Request $request)
    {
        Gate::authorize('delete', $workExperience);
        $workExperience->delete();
        return response()->json([
            'status' => 'success',
            'message' => "Work experience deleted successfully!"
        ]);
    }
}
