<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserRole;

class StaffMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get shop_id from route or request
        $shopId = $user->shops()->first()->id;

        if (!$shopId) {
            return response()->json([
                'message' => 'not allowed to access'
            ], 400);
        }

        $role = UserRole::where('user_id', $user->id)
            ->where('shop_id', $shopId)
            ->whereIn('role', ['STAFF', 'OWNER']) // Staff OR Owner can pass
            ->exists();

        if (!$role) {
            return response()->json([
                'message' => 'Staff or Owner role required'
            ], 403);
        }

        return $next($request);
    }
}
