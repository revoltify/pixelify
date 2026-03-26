<?php

declare(strict_types=1);

namespace Revoltify\Pixelify\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Revoltify\Pixelify\Traits\HasTracking;
use Symfony\Component\HttpFoundation\Response;

final class FacebookTrackingMiddleware
{
    use HasTracking;

    public function __construct(private readonly bool $isQueued) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->isQueued) {
            try {
                $this->handleTracking($request);
            } catch (Exception) {
                //
            }
        }

        return $next($request);
    }
}
