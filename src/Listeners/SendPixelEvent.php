<?php

declare(strict_types=1);

namespace Revoltify\Pixelify\Listeners;

use Revoltify\Pixelify\Events\PixelEventOccurred;
use Revoltify\Pixelify\Http\Client\FacebookClient;
use Illuminate\Contracts\Queue\ShouldQueue;

final readonly class SendPixelEvent implements ShouldQueue
{
    public function __construct(private FacebookClient $client, private bool $isQueued) {}

    public function handle(PixelEventOccurred $event): void
    {
        $this->client->sendEvent($event->eventData);
    }

    public function shouldQueue(PixelEventOccurred $event): bool
    {
        return $this->isQueued;
    }
}
