<?php

namespace Revoltify\Pixelify\Listeners;

use Revoltify\Pixelify\Events\PixelEventOccurred;
use Revoltify\Pixelify\Http\Client\FacebookClient;

class SendPixelEvent
{
    public function __construct(private FacebookClient $client) {}

    public function handle(PixelEventOccurred $event): void
    {
        $this->client->sendEvent($event->eventData);
    }
}
