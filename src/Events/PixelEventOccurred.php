<?php

declare(strict_types=1);

namespace Revoltify\Pixelify\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Revoltify\Pixelify\DTO\EventData;

final class PixelEventOccurred
{
    use Dispatchable, SerializesModels;

    public function __construct(public EventData $eventData) {}
}
