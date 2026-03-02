<?php

namespace Revoltify\Pixelify\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Revoltify\Pixelify\PixelifyServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.
     *
     * @param Application $app
     * @return array<int, class-string<ServiceProvider>>
     */
    protected function getPackageProviders($app)
    {
        return [
            PixelifyServiceProvider::class,
        ];
    }
}
