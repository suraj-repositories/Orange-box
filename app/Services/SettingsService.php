<?php

namespace App\Services;

interface SettingsService
{
    function loadSettings();

    function get(string $key, $default = null);

    function refresh();


}
