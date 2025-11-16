<?php

namespace App\Services\Impl;

use App\Models\Settings;
use App\Models\SettingsCategory;
use App\Services\SettingsService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SettingsServiceImpl implements SettingsService
{
    protected Collection $settings;
    protected Collection $settingsCategories;

    public function __construct()
    {
        $this->loadSettings();
    }

    public function loadSettings(): void
    {
        $this->settings = Cache::rememberForever(
            'settings.global',
            fn() => Settings::all()->keyBy('key')
        );
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $setting = $this->settings->get($key);

        if (!$setting) {
            return $default;
        }
        if ($setting->is_enabled && $setting->value === null) {
            return true;
        }

        return $setting->value ?? $default;
    }


    public function set(string $key, mixed $value): mixed
    {
        $setting = Settings::where('key', $key)->first();

        if (!$setting) {
            throw new \Exception("Setting '{$key}' does not exist.");
        }

        if (is_bool($value)) {
            $setting->update([
                'is_enabled' => $value,
                'value' => null,
            ]);
        } else {
            $setting->update([
                'value' => $value,
            ]);
        }

        $this->settings->put($key, $setting);
        Cache::put('settings.global', $this->settings);

        return $setting;
    }

    public function all(): array
    {
        return $this->settings->pluck('value', 'key')->toArray();
    }

    public function refresh(): void
    {
        Cache::forget('settings.global');
        $this->loadSettings();
    }

    public function allGroupedByCategory(): Collection
    {
        return $this->settingsCategories->mapWithKeys(
            fn($cat) => [$cat->name => $cat->settings->pluck('value', 'key')]
        );
    }

    public function getDescription(string $key, mixed $default = null): mixed
    {
        return $this->settings->get($key)?->description ?? $default;
    }
}
