<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ManagerOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role !== 'manager') {
            // Redirect to user's appropriate dashboard
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard')->with('error', 'Access denied. You can only access admin areas.');
                case 'user':
                    return redirect()->route('user.dashboard')->with('error', 'Access denied. You can only access user areas.');
                default:
                    return redirect()->route('user.dashboard')->with('error', 'Access denied.');
            }
        }

        return $next($request);
    }
}
