<?php

namespace App\Services;

use App\Models\Settings;
use Illuminate\Support\Collection;

interface SettingsService
{
    function loadSettings();

    function get(string $key, $default = null);

    function getDescription(string $key, $default = null);

    function set(string $key, $value): Settings;

    function allGroupedByCategory(): Collection;

    function all(): array;

    function refresh(): void;
}
