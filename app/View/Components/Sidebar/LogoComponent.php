<?php

namespace App\View\Components\Sidebar;

use App\Facades\Setting;
use App\Models\AppTheme;
use App\Models\UserSetting;
use App\Services\ThemeService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LogoComponent extends Component
{
    public $logoLight;
    public $logoDark;
    public $logoSm;

    public function __construct(ThemeService $themeService)
    {
        $this->logoLight = 'assets/images/logo-light.png';
        $this->logoDark  = 'assets/images/logo-dark.png';
        $this->logoSm    = 'assets/images/logo-sm.png';

        $theme = $themeService->current();

        if ($theme) {
            $this->logoLight = $theme->logo_light ?: $this->logoLight;
            $this->logoDark  = $theme->logo_dark ?: $this->logoDark;
            $this->logoSm    = $theme->logo_sm ?: $this->logoSm;
        }
    }

    public function render(): View|Closure|string
    {
        return view('components.sidebar.logo-component');
    }
}
