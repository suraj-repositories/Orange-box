<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\SettingsCategory;
use App\Models\User;

class SettingsController extends Controller
{
    //
    public function index(User $user)
    {
        $appSettings = SettingsCategory::orderBy('name', 'asc')->with('settings')->get();
        $userSettings = Settings::leftJoin('user_settings', function ($join) use ($user) {
            $join->on('user_settings.setting_id', '=', 'settings.id')
                ->where('user_settings.user_id', '=', $user->id);
        })
            ->select(
                'settings.key',
                'settings.is_enabled',
                'user_settings.value',
                'settings.value as default_value',
                'settings.value_model'
            )
            ->get()
            ->transform(function ($setting) {
                if (empty($setting->value)) {
                    $setting->value = false;
                }
                return $setting;
            })
            ->pluck('value', 'key');

        return view(
            'user.account.settings.settings',
            [
                'appSettings' => $appSettings,
                'userSettings' => $userSettings,
            ]
        );
    }
}
