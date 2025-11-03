<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\PasswordLocker;
use App\Models\ProjectBoard;
use App\Models\SocialMediaPlatform;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserDetails;
use App\Models\UserSkill;
use App\Models\UserSocialMediaLink;
use App\Models\WorkExperience;
use App\Services\FileService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    //
    public function index(User $user)
    {
        $personalInfo = $user->details;
        $primaryAddress = $user->primaryAddress();
        $socialMediaPlatforms = SocialMediaPlatform::orderBy('name', 'ASC')->get();
        $skills = UserSkill::where('user_id', $user->id)->latest()->pluck('level', 'skill');
        $arr = $skills->toArray();
        arsort($arr);
        $topSkills = collect($arr)->take(6);

        $projects = ProjectBoard::where('user_id', $user->id)->latest()->take(4)->get();

        $userSocialMediaLinks = UserSocialMediaLink::where('user_id', $user->id)->get();

        foreach ($socialMediaPlatforms as $socialMediaPlatform) {
            foreach ($userSocialMediaLinks as $userSocialMediaLink) {
                if ($socialMediaPlatform->id === $userSocialMediaLink->social_media_platform_id) {
                    $socialMediaPlatform->user_link = $userSocialMediaLink->url;
                }
            }
        }

        return view('user.account.profile.user_profile', [
            'user' => $user,
            'personalInfo' => $personalInfo,
            'address' => $primaryAddress,
            'socialMediaPlatforms' => $socialMediaPlatforms,
            'userSocialMediaLinks' =>  $userSocialMediaLinks,
            'skills' => $skills,
            'experties' => $topSkills,
            'projects' => $projects,
            'user_skills' => UserSkill::where('user_id', $user->id)->get(),
            'passwords' => PasswordLocker::where('user_id', $user->id)->paginate(10),
            'experiences' => WorkExperience::where('user_id', $user->id)->orderByDesc('id')->get(),
            'educations' => Education::where('user_id', $user->id)->orderBy('id', 'desc')->get()
        ]);
    }

    public function updatePersonalInformation(User $user, Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'gender' => 'nullable|string|in:male,female,other',
            'tag_line' => 'nullable|string|max:256',
            'contact' => 'nullable|string|max:20',
            'public_email' => 'nullable|email|max:256',
            'bio' => 'nullable|string|max:3000'

        ]);
        try {
            $user->details()->updateOrCreate(['user_id' => $user->id], $validated);
            return redirect()->back()->with('success', 'Personal information updated successfully!');
        } catch (Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    public function saveAddress(User $user, Request $request)
    {
        $validated = $request->validate([
            'line1' => 'required|string|max:256',
            'line2' => 'nullable|string|max:256',
            'city' => 'required|string|max:256',
            'postal_code' => 'required|numeric',
            'state' => 'required|string|max:256',
            'country' => 'required|string|max:256',
        ]);
        try {
            $validated = array_merge([
                'is_primary' => true,
                'user_id' => $user->id,
            ], $validated);


            $newAddress = UserAddress::create($validated);
            UserAddress::where('user_id', $user->id)->where('id', '!=', $newAddress->id)->update(['is_primary' => false]);

            return redirect()->back()->with('success', 'Address updated successfully!');
        } catch (Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

    public function updateSocialMediaLinks(User $user, Request $request)
    {
        $validated = $request->validate([
            'platform_id' => 'array',
            'platform_id.*' => 'numeric|exists:social_media_platforms,id',
            'social_media_link' => 'array',
            'social_media_link.*' => 'nullable|url|max:256',
        ]);

        try {
            foreach ($validated['platform_id'] as $index => $platformId) {

                if (empty($validated['social_media_link'][$index])) {
                    UserSocialMediaLink::where('user_id', $user->id)
                                        ->where('social_media_platform_id', $platformId)
                                        ->forceDelete();
                    continue;
                }
                UserSocialMediaLink::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'social_media_platform_id' => $platformId
                    ],
                    [
                        'user_id' => $user->id,
                        'social_media_platform_id' => $platformId,
                        'url' => $validated['social_media_link'][$index]
                    ]
                );
            }
            return redirect()->back()->with('success', "Social media links updated successfully!");
        } catch (Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage());
        }
    }

     public function updateProfileImage(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|mimes:png,jpg,svg,webp,avif,gif,ico|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }


        try {
            $this->fileService->deleteIfExists($user->avatar);

            $filePath = $this->fileService->uploadFile($request->file('image'), "users", 'public');

            User::where('id', $user->id)->update(['avatar' => $filePath]);

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully!',
                'image_url' => asset('storage/' . $filePath)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error uploading file: ' . $e->getMessage()
            ], 500);
        }
    }

}
