<?php

namespace Revoltify\Pixelify\DTO;

use Illuminate\Support\Carbon;

class EventData
{
    public const ACTION_SOURCE_WEBSITE = 'website';

    public function __construct(
        public string $eventName,
        public string $eventId,
        public ?UserData $userData = null,
        public ?ProductData $productData = null,
        public ?string $eventSourceUrl = null,
        public string $actionSource = self::ACTION_SOURCE_WEBSITE,
    ) {}

    public function toArray(): array
    {
        $data = [
            'event_name' => $this->eventName,
            'event_time' => Carbon::now()->timestamp,
            'event_id' => $this->eventId,
            'event_source_url' => $this->eventSourceUrl ?? request()->url(),
            'action_source' => $this->actionSource,
        ];

        // Add user data
        if ($this->userData) {
            $data['user_data'] = $this->userData->toArray();
        }

        // Add custom data from product
        if ($this->productData) {
            $data['custom_data'] = $this->productData->toArray();
        }

        return array_filter($data);
    }
}
