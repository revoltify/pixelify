<?php

declare(strict_types=1);

namespace Revoltify\Pixelify\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Revoltify\Pixelify\Events\PixelEventOccurred;
use Revoltify\Pixelify\Http\Client\FacebookClient;

final readonly class SendPixelEvent implements ShouldQueue
{
    public function __construct(private FacebookClient $client) {}

    public function handle(PixelEventOccurred $event): void
    {
        $this->client->sendEvent($event->eventData);
    }

    public function shouldQueue(PixelEventOccurred $event): bool
    {
        return config()->boolean('pixelify.queued_listener_mode', false);
    }
}
