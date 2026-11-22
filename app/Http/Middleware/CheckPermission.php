<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SecurityEvent;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->guest(route('login'));
        }

        // Super Admin has unrestricted bypass
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Check if user has permission
        if (!$user->hasPermission($permission)) {
            SecurityEvent::log('permission_denied', 'warning', [
                'user_id' => $user->id,
                'role' => $user->role,
                'required_permission' => $permission,
                'path' => $request->path(),
            ], $user->id);

            abort(403, "Access Denied: You do not have the required permission ({$permission}).");
        }

        return $next($request);
    }
}
