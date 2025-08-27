<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetArea
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, ?string $area = null): Response
    {
        if ($area) {
            session(['__area' => $area]);
        }

        return $next($request);
    }
}
