<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\PasswordLocker;
use App\Models\SocialMediaPlatform;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserDetails;
use App\Models\UserSkill;
use App\Models\UserSocialMediaLink;
use App\Models\WorkExperience;
use Exception;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    public function index(User $user)
    {
        $personalInfo = $user->details;
        $primaryAddress = $user->primaryAddress();
        $socialMediaPlatforms = SocialMediaPlatform::orderBy('name', 'ASC')->get();

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


}
