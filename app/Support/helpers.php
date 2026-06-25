<?php

use App\Services\Impl\ThemeServiceImpl;
use App\Services\ThemeService;
use Illuminate\Support\Facades\Auth;

if (! function_exists('authRoute')) {
    /**
     * Generate a URL for a route, automatically injecting the authenticated user's ID
     *
     * Example:
     *   authRoute('user.daily-digest');
     *   // Equivalent to: route('user.daily-digest', Auth::id());
     *
     * @param  string  $name       The name of the route
     * @param  array   $parameters Additional route parameters (after user ID)
     * @param  bool    $absolute   Whether to generate an absolute URL
     * @return string
     */
    function authRoute(string $name, array $parameters = [], bool $absolute = true): string
    {
        $parameters = array_merge(["userid" => Auth::user()->username], $parameters);

        return route($name, $parameters, $absolute);
    }
}

function current_theme_key()
{
    $th = new ThemeServiceImpl();

    return $th->current()->theme_key;
}

function theme_asset(string $file): string
{
    $theme = current_theme_key();

    $path = "assets/images/themes/{$theme}/{$file}";

    if (file_exists(public_path($path))) {
        return asset($path);
    }

    return asset("assets/images/defaults/placeholder-600x400.svg");
}
