<?php

namespace Revoltify\Pixelify\Http\Client;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Revoltify\Pixelify\DTO\EventData;

class FacebookClient
{
    private PendingRequest $client;

    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = sprintf(
            'https://graph.facebook.com/%s/%s/events',
            config('pixelify.api_version'),
            config('pixelify.pixel_id')
        );

        $this->client = Http::withHeaders(['Content-Type' => 'application/json']);
    }

    public function sendEvent(EventData $eventData): array
    {
        $payload = [
            'data' => [$eventData->toArray()],
            'access_token' => config('pixelify.access_token'),
        ];

        if (config('pixelify.test_event_code')) {
            $payload['test_event_code'] = config('pixelify.test_event_code');
        }

        $response = $this->client->post($this->baseUrl, $payload);

        if (config('pixelify.debug')) {
            logger()->debug('Facebook Pixel API Response', [
                'payload' => $payload,
                'response' => $response->json(),
            ]);
        }

        return $response->json();
    }
}
