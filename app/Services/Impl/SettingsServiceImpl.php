<?php

namespace App\Services\Impl;

use App\Models\Settings;
use App\Models\SettingsCategory;
use App\Services\SettingsService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SettingsServiceImpl implements SettingsService
{
    /**
     * @var Collection
     */
    protected $settings;

    /**
     * @var Collection
     */
    protected $settingsCategories;

    /**
     * Initialize service and load settings.
     */
    public function __construct()
    {
        $this->loadSettings();
    }

    /**
     * Load all settings and categories (from cache or DB).
     */
    public function loadSettings(): void
    {
        // You can cache results for performance
        $this->settingsCategories = Cache::remember('settings_categories', 3600, function () {
            return SettingsCategory::with('settings')->get();
        });

        $this->settings = Cache::remember('settings_all', 3600, function () {
            return Settings::all()->keyBy('key');
        });
    }

    /**
     * Get a setting by key.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $setting = $this->settings->get($key);

        return $setting ? $setting->value : $default;
    }

    /**
     * Get a setting description by key.
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function getDescription(string $key, $default = null)
    {
        $setting = $this->settings->get($key);

        return $setting ? $setting->description : $default;
    }

    /**
     * Set or update a setting value in DB and cache.
     *
     * @param string $key
     * @param mixed $value
     * @return Settings
     */
    public function set(string $key, $value): Settings
    {
        $setting = Settings::updateOrCreate(['key' => $key], ['value' => $value]);

        $this->settings->put($key, $setting);
        Cache::put('settings_all', $this->settings, 3600);

        return $setting;
    }

    /**
     * Get all settings grouped by category.
     *
     * @return Collection
     */
    public function allGroupedByCategory(): Collection
    {
        return $this->settingsCategories->mapWithKeys(function ($category) {
            return [$category->name => $category->settings->pluck('value', 'key')];
        });
    }

    /**
     * Get all settings as key-value pairs.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->settings->pluck('value', 'key')->toArray();
    }

    /**
     * Refresh settings and categories (reload + clear cache).
     */
    public function refresh(): void
    {
        Cache::forget('settings_all');
        Cache::forget('settings_categories');
        $this->loadSettings();
    }
}
