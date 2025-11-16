<?php

namespace App\Services;

use Illuminate\Support\Collection;

interface SettingsService
{
    public function loadSettings(): void;

    public function get(string $key, mixed $default = null): mixed;

    public function set(string $key, mixed $value): mixed;

    public function allGroupedByCategory(): Collection;

    public function all(): array;

    public function refresh(): void;

    public function getDescription(string $key, mixed $default = null): mixed;
}
