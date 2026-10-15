<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // If no roles specified, allow request
        if (empty($roles)) {
            return $next($request);
        }

        // Admin always has access unless route is explicitly limited
        if (in_array($user->role, $roles, true)) {
            return $next($request);
        }

        abort(403, __('Access denied. You do not have permission to access this resource.'));
    }
}
