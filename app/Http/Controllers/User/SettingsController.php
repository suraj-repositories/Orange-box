<?php

namespace App\Http\Controllers\User;

use App\Facades\Setting;
use App\Http\Controllers\Controller;
use App\Models\SettingsCategory;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    //
    public function index()
    {
        $appSettings = SettingsCategory::orderBy('name', 'asc')->with('settings')->get();
        $userSettings = Setting::allGroupedByCategory();

        return view(
            'user.account.settings.settings',
            [
                'settings' => $userSettings,
                'appSettings' => $appSettings
            ]
        );
    }
}
