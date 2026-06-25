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


    public function __construct() {}

    public function render(): View|Closure|string
    {
        return view('components.sidebar.logo-component');
    }
}
