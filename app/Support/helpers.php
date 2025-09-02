<?php

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
        $parameters = array_merge(["userid" => Auth::id()], $parameters);

        return route($name, $parameters, $absolute);
    }
}
