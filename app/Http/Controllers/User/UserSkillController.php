<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSkill;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserSkillController extends Controller
{
    //
    public function store(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'skill' => [
                'required',
                'string',
                'max:256',
                Rule::unique('user_skills', 'skill')->where(
                    fn($query) =>
                    $query->where('user_id', $user->id)
                ),
            ],
            'level' => 'nullable|numeric|min:0|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'field' => $validator->errors()->keys()[0],
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $userSkill = UserSkill::create([
                'user_id' => $user->id,
                'skill' => $request->skill,
                'level' => $request->level
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Skill Created Successfully!',
                'skill' => $userSkill
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'status' => 'error',
                'message' => $ex->getMessage()
            ]);
        }
    }

    public function destroy(User $user, UserSkill $userSkill, Request $request){


        $userSkill->delete();
        return response()->json([
            'status' => 'success',
            'message' => "Skill deleted successfully!"
        ]);
    }
}
