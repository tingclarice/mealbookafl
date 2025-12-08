<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\UserRole;

class OwnerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Get shop_id from route or request
        $shopId = $user->shops()->first()->id;;

        if (!$shopId) {
            return response()->json([
                'message' => 'shop_id is required'
            ], 400);
        }

        $isOwner = UserRole::where('user_id', $user->id)
            ->where('shop_id', $shopId)
            ->where('role', 'OWNER')
            ->exists();

        if (!$isOwner) {
            return response()->json([
                'message' => 'Owner role required'
            ], 403);
        }

        return $next($request);
    }
}
