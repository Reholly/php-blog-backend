<?php

namespace App\Http\Middlewares;

use Closure;
use Symfony\Component\HttpFoundation\Response;

class RequireRoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        if (!in_array($user->role, $roles)) {
            return response()->json(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
