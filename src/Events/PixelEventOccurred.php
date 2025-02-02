<?php

namespace Revoltify\Pixelify\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Revoltify\Pixelify\DTO\EventData;

class PixelEventOccurred
{
    use Dispatchable, SerializesModels;

    public function __construct(public EventData $eventData) {}
}
