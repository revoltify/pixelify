<?php

namespace Revoltify\Pixelify\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

trait HasTracking
{
    private string $fbcCookieName = '_fbc';
    
    private string $fbpCookieName = '_fbp';

    private int $cookieLifetime = 7776000; // 90 days in seconds

    public function handleTracking(Request $request): void
    {
        $this->handleFbc($request);
        $this->handleFbp($request);
    }

    private function handleFbc(Request $request): void
    {
        $fbclid = $request->query('fbclid');

        if ($fbclid) {
            $formattedFbc = $this->formatFbc($fbclid);
            Cookie::queue($this->fbcCookieName, $formattedFbc, $this->cookieLifetime);
        }
    }

    private function handleFbp(Request $request): void
    {
        if (! $request->cookie($this->fbcCookieName)) {
            $formattedFbp = $this->generateFbp();
            Cookie::queue($this->fbpCookieName, $formattedFbp, $this->cookieLifetime);
        }
    }

    private function formatFbc(string $fbclid): string
    {
        $version = 'fb';
        $subdomainIndex = 1; // for example.com
        $creationTime = round(microtime(true) * 1000);

        return "{$version}.{$subdomainIndex}.{$creationTime}.{$fbclid}";
    }

    private function generateFbp(): string
    {
        $version = 'fb';
        $subdomainIndex = 1; // for example.com
        $creationTime = round(microtime(true) * 1000);
        $randomNumber = mt_rand(1000000000, 9999999999);

        return "{$version}.{$subdomainIndex}.{$creationTime}.{$randomNumber}";
    }
}
