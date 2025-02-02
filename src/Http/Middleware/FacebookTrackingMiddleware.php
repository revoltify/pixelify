<?php

namespace Revoltify\Pixelify\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Revoltify\Pixelify\Traits\HasTracking;
use Symfony\Component\HttpFoundation\Response;

class FacebookTrackingMiddleware
{
    use HasTracking;

    public function handle(Request $request, Closure $next): Response
    {
        try {
            $this->handleTracking($request);
        } catch (\Exception $e) {
            //
        }

        return $next($request);
    }
}
