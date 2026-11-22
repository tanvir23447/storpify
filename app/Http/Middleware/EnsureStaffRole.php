<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SecurityEvent;

class EnsureStaffRole
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->guest(route('login'));
        }

        if (!$user->isStaff()) {
            SecurityEvent::log('unauthorized_admin_access_attempt', 'warning', [
                'user_id' => $user->id,
                'path' => $request->path(),
            ], $user->id);

            abort(403, 'Unauthorized access: Staff privileges required.');
        }

        if ($user->status !== 'active') {
            abort(403, 'Account is suspended or inactive.');
        }

        return $next($request);
    }
}
