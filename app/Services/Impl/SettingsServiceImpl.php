<?php

namespace App\Services\Impl;

use App\Models\Settings;
use App\Services\SettingsService as ServicesSettingsService;

class SettingsService implements ServicesSettingsService
{
    protected $settings;

    public function __construct()
    {
        $this->loadSettings();
    }

    function loadSettings() {
        $this->settings = Settings::all()->pluck('value', 'key')->toArray();
    }

    function get(string $key, $default = null) {
        return $this->settings[$key] ?? $default;
    }

    function refresh() {
          $this->loadSettings();
    }
}
