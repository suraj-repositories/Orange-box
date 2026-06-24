<?php

namespace App\Services;

use App\Models\AppTheme;

interface ThemeService
{
    public function current(): ?AppTheme;
}
