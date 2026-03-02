<?php

declare(strict_types=1);

namespace Revoltify\Pixelify\Listeners;

use Revoltify\Pixelify\Events\PixelEventOccurred;
use Revoltify\Pixelify\Http\Client\FacebookClient;

final readonly class SendPixelEvent
{
    public function __construct(private FacebookClient $client) {}

    public function handle(PixelEventOccurred $event): void
    {
        $this->client->sendEvent($event->eventData);
    }
}
