<?php

namespace Revoltify\Pixelify\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

trait HasTracking
{
    private const FBC_COOKIE_NAME = '_fbc';

    private const FBP_COOKIE_NAME = '_fbp';

    private const COOKIE_LIFETIME = 7776000; // 90 days in seconds

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
            Cookie::queue(self::FBC_COOKIE_NAME, $formattedFbc, self::COOKIE_LIFETIME);
        }
    }

    private function handleFbp(Request $request): void
    {
        if (! $request->cookie(self::FBP_COOKIE_NAME)) {
            $formattedFbp = $this->generateFbp();
            Cookie::queue(self::FBP_COOKIE_NAME, $formattedFbp, self::COOKIE_LIFETIME);
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
