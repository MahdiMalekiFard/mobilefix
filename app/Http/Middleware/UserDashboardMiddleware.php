<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserDashboardMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            // Check if user has set a password
            if (!auth()->user()->hasPasswordSet()) {
                // Allow access to dashboard (where password setup form is shown)
                if ($request->routeIs('user.dashboard')) {
                    return $next($request);
                }
                
                // Redirect to dashboard for other routes if password not set
                return redirect(route('user.dashboard'));
            }
            
            return $next($request);
        }

        return redirect(route('user.auth.login'));
    }
}
