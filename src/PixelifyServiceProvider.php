<?php

namespace Revoltify\Pixelify;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Revoltify\Pixelify\Commands\PixelifySetupCommand;
use Revoltify\Pixelify\Contracts\PixelifyInterface;
use Revoltify\Pixelify\Http\Client\FacebookClient;
use Revoltify\Pixelify\Http\Middleware\FacebookTrackingMiddleware;
use Revoltify\Pixelify\Services\PixelifyService;

class PixelifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register config
        $this->mergeConfigFrom(
            __DIR__.'/../config/pixelify.php',
            'pixelify'
        );

        // Register service
        $this->app->singleton(PixelifyInterface::class, function ($app) {
            return new PixelifyService;
        });

        // Register Facebook client
        $this->app->singleton(FacebookClient::class, function ($app) {
            return new FacebookClient;
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            // Publish config
            $this->publishes([
                __DIR__.'/../config/pixelify.php' => config_path('pixelify.php'),
            ], 'pixelify-config');

            // Register commands
            $this->commands([
                PixelifySetupCommand::class,
            ]);
        }

        // Register Facebook tracking middleware to the 'web' middleware group
        $kernel = $this->app->make(Kernel::class);
        $kernel->appendMiddlewareToGroup('web', FacebookTrackingMiddleware::class);

        // Register event listeners
        $this->app['events']->listen(
            \Revoltify\Pixelify\Events\PixelEventOccurred::class,
            \Revoltify\Pixelify\Listeners\SendPixelEvent::class
        );
    }
}
