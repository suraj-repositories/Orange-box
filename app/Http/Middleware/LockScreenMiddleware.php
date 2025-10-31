<?php

namespace App\Http\Middleware;

use App\Models\ScreenLock;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LockScreenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        if (empty($user->keys)) {
            return $next($request);
        }

        $screenLock = ScreenLock::where('user_id', $user->id)
            ->latest()
            ->first();

        if (!$screenLock) {
            return $next($request);
        }

        if ($screenLock->unlocked) {
            return $next($request);
        }

        if ($request->routeIs('lock-screen.*')) {
            return $next($request);
        }

        $isLocked = (
            (!$screenLock->unlocked) &&
            (
                (isset($screenLock->expires_at) && $screenLock->expires_at >= now()) ||
                empty($screenLock->expires_at)
            )
        );

        if ($isLocked) {
            return redirect()->route('lock-screen.index');
        }

        if ($screenLock->unlocked && $screenLock->redirect_url && $request->fullUrl() !== $screenLock->redirect_url) {
            return redirect()->to($screenLock->redirect_url);
        }

        return $next($request);
    }
}
