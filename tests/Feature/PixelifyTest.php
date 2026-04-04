<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Event;
use Revoltify\Pixelify\DTO\EventData;
use Revoltify\Pixelify\DTO\ProductData;
use Revoltify\Pixelify\DTO\UserData;
use Revoltify\Pixelify\Events\PixelEventOccurred;
use Revoltify\Pixelify\Facades\Pixelify;
use Revoltify\Pixelify\Http\Middleware\FacebookTrackingMiddleware;
use Revoltify\Pixelify\Listeners\SendPixelEvent;

dataset('listener modes', [
    'queued mode' => [true],
    'sync mode' => [false],
]);

dataset('tracking scenarios', [
    'page view' => [
        fn (): mixed => Pixelify::pageView(new UserData(firstName: 'John', lastName: 'Doe', email: 'test@example.com')),
        fn (PixelEventOccurred $event): bool => $event->eventData->eventName === 'PageView',
    ],
    'view content' => [
        fn (): mixed => Pixelify::viewContent(new ProductData(productId: '123', price: 99.99)),
        fn (PixelEventOccurred $event): bool => $event->eventData->eventName === 'ViewContent'
            && $event->eventData->productData->productId === '123',
    ],
    'add to cart' => [
        fn (): mixed => Pixelify::addToCart(new ProductData(productId: '123', price: 99.99, quantity: 2)),
        fn (PixelEventOccurred $event): bool => $event->eventData->eventName === 'AddToCart'
            && $event->eventData->productData->quantity === 2,
    ],
    'initiate checkout' => [
        fn (): mixed => Pixelify::initiateCheckout(
            new ProductData(productId: '123', price: 99.99),
            new UserData(email: 'test@example.com')
        ),
        fn (PixelEventOccurred $event): bool => $event->eventData->eventName === 'InitiateCheckout'
            && $event->eventData->userData->email === 'test@example.com',
    ],
    'purchase' => [
        fn (): mixed => Pixelify::purchase(new ProductData(productId: '123', price: 99.99, currency: 'USD')),
        fn (PixelEventOccurred $event): bool => $event->eventData->eventName === 'Purchase'
            && $event->eventData->productData->currency === 'USD',
    ],
]);

describe('event tracking', function (): void {
    beforeEach(function (): void {
        Event::fake();
    });

    test('it dispatches the expected pixel event', function (bool $isQueued, Closure $trackEvent, Closure $assertEvent): void {
        config()->set('pixelify.queued_listener_mode', $isQueued);

        $trackEvent();

        Event::assertDispatched(PixelEventOccurred::class, fn (PixelEventOccurred $event): bool => $assertEvent($event));
    })->with('listener modes')->with('tracking scenarios');
});

describe('listener queueing', function (): void {
    test('it follows the configured queue mode', function (bool $isQueued): void {
        config()->set('pixelify.queued_listener_mode', $isQueued);

        $listener = app(SendPixelEvent::class);
        $event = new PixelEventOccurred(new EventData(eventName: 'PageView', eventId: 'test-id'));

        expect($listener->shouldQueue($event))->toBe($isQueued);
    })->with('listener modes');
});

describe('middleware tracking', function (): void {
    test('it queues tracking cookies', function (bool $isQueued): void {
        config()->set('pixelify.queued_listener_mode', $isQueued);

        $middleware = app(FacebookTrackingMiddleware::class);
        $middleware->handle(Request::create('/?fbclid=test123'), fn ($request) => response('ok'));

        expect(Cookie::queued('_fbc'))->not->toBeNull();
        expect(Cookie::queued('_fbp'))->not->toBeNull();
    })->with('listener modes');
});
