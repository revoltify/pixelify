<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Revoltify\Pixelify\DTO\ProductData;
use Revoltify\Pixelify\DTO\UserData;
use Revoltify\Pixelify\Events\PixelEventOccurred;
use Revoltify\Pixelify\Services\PixelifyService;

test('it generates unique event IDs', function (): void {
    Event::fake();
    $service = new PixelifyService;
    $productData = new ProductData(
        productId: '123',
        price: 99.99
    );

    $service->viewContent($productData);
    $service->viewContent($productData);

    $eventIds = [];
    Event::assertDispatched(PixelEventOccurred::class, function ($event) use (&$eventIds): true {
        $eventIds[] = $event->eventData->eventId;

        return true;
    });

    expect(count(array_unique($eventIds)))->toBe(2);
});

test('it includes user data when provided', function (): void {
    Event::fake();
    $service = new PixelifyService;
    $userData = new UserData(email: 'test@example.com');

    $service->pageView($userData);

    Event::assertDispatched(PixelEventOccurred::class, fn ($event): bool => $event->eventData->userData !== null
    && $event->eventData->userData->email === 'test@example.com');
});
