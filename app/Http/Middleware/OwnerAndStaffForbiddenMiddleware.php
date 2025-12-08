<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserRole;

class OwnerAndStaffForbiddenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Check if user is OWNER or STAFF in ANY shop
        $hasForbiddenRole = UserRole::where('user_id', $user->id)
            ->whereIn('role', ['OWNER', 'STAFF'])
            ->exists();

        if ($hasForbiddenRole) {
            return response()->json([
                'message' => 'Access forbidden for Owners and Staff'
            ], 403);
        }

        return $next($request);
    }
}
