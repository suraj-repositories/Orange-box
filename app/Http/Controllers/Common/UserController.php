<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\ProjectBoard;
use App\Models\SocialMediaPlatform;
use App\Models\User;
use App\Models\UserSkill;
use App\Models\UserSocialMediaLink;
use App\Models\WorkExperience;
use App\Services\ThemeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Response;

class UserController extends Controller
{
    //
    public function searchUsers(Request $request)
    {
        if (!$request->has('username') || !Auth::check()) {
            abort(403, "Username is required!");
        }

        $users = User::where('username', 'like', "%$request->username%")->select('id', 'username', 'avatar')->take(5)->get();

        foreach ($users as $user) {
            $user->avatar = $user->profilePicture();
        }

        return response()->json([
            'status' => 200,
            'data' => $users
        ]);
    }

    public function index()
    {
        $authUser = Auth::user();

        $mutualConnections = collect();
        $suggestions = collect();

        $following = $authUser
            ? $authUser->following()->paginate(10)
            : collect();

        $followers = $authUser
            ? $authUser->followers()->paginate(10)
            : collect();

        $suggestions = User::role('user')
            ->when($authUser, function ($query) use ($authUser) {
                $query->where('id', '!=', $authUser->id)
                    ->whereNotIn('id', $authUser->following()->pluck('users.id'));
            })
            ->paginate(10);

        $mutualConnections = $authUser
            ? $authUser->following()
            ->whereIn('users.id', function ($query) use ($authUser) {
                $query->select('follower_id')
                    ->from('follows')
                    ->where('following_id', $authUser->id);
            })
            ->take(10)
            ->get()
            : collect();

        return view('user.users.show-users', compact(
            'following',
            'followers',
            'suggestions',
            'mutualConnections'
        ));
    }

    public function pageableUsersList($type, Request $request)
    {
        $authUser = auth()->user();

        switch ($type) {
            case 'followers':
                $users = $authUser
                    ? $authUser->followers()->paginate(20)
                    : collect();
                $title = 'Followers';
                break;

            case 'following':
                $users = $authUser
                    ? $authUser->following()->paginate(20)
                    : collect();
                $title = 'Following';
                break;

            case 'mutual':
                $users = $authUser
                    ? $authUser->following()
                    ->whereIn('users.id', function ($query) use ($authUser) {
                        $query->select('follower_id')
                            ->from('follows')
                            ->where('following_id', $authUser->id);
                    })
                    ->paginate(20)
                    : collect();
                $title = 'Mutual Connections';
                break;

            default:
                $users = User::role('user')
                    ->when($authUser, function ($query) use ($authUser) {
                        $query->where('id', '!=', $authUser->id)
                            ->whereNotIn('id', $authUser->following()->pluck('users.id'));
                    })
                    ->paginate(20);

                $title = 'Suggestions';
                break;
        }

        return view('user.users.pageable-users-list', compact('users', 'title', 'type'));
    }

    public function showProfile(User $user)
    {
        if (!$user->hasRole('user')) {
            abort(403, 'Forbidden');
        }

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

        return view('user.users.show-user-profile', [
            'user' => $user,
            'personalInfo' => $personalInfo,
            'address' => $primaryAddress,
            'socialMediaPlatforms' => $socialMediaPlatforms,
            'userSocialMediaLinks' =>  $userSocialMediaLinks,
            'skills' => $skills,
            'experties' => $topSkills,
            'projects' => $projects,
            'user_skills' => UserSkill::where('user_id', $user->id)->get(),
            'experiences' => WorkExperience::where('user_id', $user->id)->orderByDesc('id')->get(),
            'educations' => Education::where('user_id', $user->id)->orderBy('id', 'desc')->get()
        ]);
    }

    public function stopImpersonation()
    {
        $adminId = session('impersonator_admin_id');

        if (!$adminId) {
            abort(403);
        }

        session()->forget('impersonator_admin_id');

        Auth::loginUsingId($adminId);

        return redirect()->route('admin.users.index');
    }

    public function profilePicSvg(Request $request, ThemeService $themeService): Response
    {
        $theme = $themeService->current();

        $bg = match ($theme?->theme_key) {
            'orange_box' => '#FF8600',
            'fresh_fiber' => '#77b255',
            'bright_red' => '#DC3545',
            'sweet_blue' => '#8b5cf6',
            default => '#FF8600',
        };

        $fg = '#FFFFFF';

        $text = strtoupper(substr($request->get('text', '?'), 0, 2));

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
    <rect width="100" height="100" fill="{$bg}" />
    <text
        x="50"
        y="50"
        text-anchor="middle"
        dominant-baseline="central"
        font-family="Arial, sans-serif"
        font-size="42"
        font-weight="700"
        fill="{$fg}">
        {$text}
    </text>
</svg>
SVG;

        return response($svg)
            ->header('Content-Type', 'image/svg+xml');
    }


}
