<?php

namespace Revoltify\Pixelify\Tests;

use Revoltify\Pixelify\PixelifyServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app)
    {
        return [
            PixelifyServiceProvider::class,
        ];
    }
}
