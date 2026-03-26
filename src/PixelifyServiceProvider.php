<?php

declare(strict_types=1);

namespace Revoltify\Pixelify;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Revoltify\Pixelify\Commands\PixelifySetupCommand;
use Revoltify\Pixelify\Contracts\PixelifyInterface;
use Revoltify\Pixelify\Events\PixelEventOccurred;
use Revoltify\Pixelify\Http\Client\FacebookClient;
use Revoltify\Pixelify\Http\Middleware\FacebookTrackingMiddleware;
use Revoltify\Pixelify\Listeners\SendPixelEvent;
use Revoltify\Pixelify\Services\PixelifyService;

final class PixelifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register config
        $this->mergeConfigFrom(
            __DIR__.'/../config/pixelify.php',
            'pixelify'
        );

        // Register service
        $this->app->singleton(PixelifyInterface::class, fn ($app): PixelifyService => new PixelifyService);

        // Register Facebook client
        $this->app->singleton(FacebookClient::class, fn ($app): FacebookClient => new FacebookClient);

        // Set SendPixelEvent listener to use queued mode based on config
        $this->app->when(SendPixelEvent::class)
            ->needs('$isQueued')
            ->give(fn (): bool => (bool) config('pixelify.queued_listener_mode', true));
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
        $this->app->make(Dispatcher::class)->listen(
            PixelEventOccurred::class,
            SendPixelEvent::class
        );
    }
}
