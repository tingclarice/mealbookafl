<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ShopApprovedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ensure user is authenticated
        $user = Auth::user();
        
        if (!$user) {
             return redirect()->route('login');
        }

        // Get the first shop (assuming one shop per owner for now based on logic)
        $shop = $user->shops->first();

        // Check if shop exists and is approved
        if ($shop && $shop->isApproved()) {
            return $next($request);
        }

        // Redirect to settings or show error if not approved
        // You might want to redirect them to a page where they can see their status
        // For now, aborting with 403 as requested or redirecting to settings
        
        // Use abort for strict access control
        // abort(403, 'Your shop is not active (Pending or Rejected). Please check your settings.');

        // Or better UX: Redirect to settings with error
        return redirect()->route('profile.edit')->with('error', 'Your shop is not active (Pending, Rejected, or Suspended).');
    }
}
