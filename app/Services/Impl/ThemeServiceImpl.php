<?php
namespace App\Services\Impl;

use App\Facades\Setting;
use App\Models\AppTheme;
use App\Models\UserSetting;
use App\Services\ThemeService;

class ThemeServiceImpl implements ThemeService
{
    public function current(): ?AppTheme
    {
        $themeId = null;

        if (auth()->check()) {
            $themeId = UserSetting::query()
                ->where('user_id', auth()->id())
                ->whereHas('settings', fn ($q) => $q->where('key', 'app_theme'))
                ->value('value');
        }

        $themeId ??= Setting::get('app_theme');

        return $themeId
            ? AppTheme::find($themeId)
            : AppTheme::first();
    }
}
