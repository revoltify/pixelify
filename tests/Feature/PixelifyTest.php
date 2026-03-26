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

describe('queued mode', function (): void {
    beforeEach(function (): void {
        config()->set('pixelify.queued_listener_mode', true);
        Event::fake();
    });

    test('it can track page view event', function (): void {
        Pixelify::pageView(new UserData(firstName: 'John', lastName: 'Doe', email: 'test@example.com'));

        Event::assertDispatched(PixelEventOccurred::class, fn ($event): bool => $event->eventData->eventName === 'PageView');
    });

    test('it can track view content event', function (): void {
        Pixelify::viewContent(new ProductData(productId: '123', price: 99.99));

        Event::assertDispatched(PixelEventOccurred::class, fn ($event): bool => $event->eventData->eventName === 'ViewContent'
            && $event->eventData->productData->productId === '123');
    });

    test('it can track add to cart event', function (): void {
        Pixelify::addToCart(new ProductData(productId: '123', price: 99.99, quantity: 2));

        Event::assertDispatched(PixelEventOccurred::class, fn ($event): bool => $event->eventData->eventName === 'AddToCart'
            && $event->eventData->productData->quantity === 2);
    });

    test('it can track initiate checkout event', function (): void {
        Pixelify::initiateCheckout(
            new ProductData(productId: '123', price: 99.99),
            new UserData(email: 'test@example.com')
        );

        Event::assertDispatched(PixelEventOccurred::class, fn ($event): bool => $event->eventData->eventName === 'InitiateCheckout'
            && $event->eventData->userData->email === 'test@example.com');
    });

    test('it can track purchase event', function (): void {
        Pixelify::purchase(new ProductData(productId: '123', price: 99.99, currency: 'USD'));

        Event::assertDispatched(PixelEventOccurred::class, fn ($event): bool => $event->eventData->eventName === 'Purchase'
            && $event->eventData->productData->currency === 'USD');
    });

    test('listener is dispatched to queue', function (): void {
        $listener = app(SendPixelEvent::class);
        $event = new PixelEventOccurred(new EventData(eventName: 'PageView', eventId: 'test-id'));

        expect($listener->shouldQueue($event))->toBeTrue();
    });

    test('middleware skips tracking cookies', function (): void {
        $middleware = new FacebookTrackingMiddleware(true);
        $middleware->handle(Request::create('/?fbclid=test123'), fn ($r) => response('ok'));

        expect(Cookie::queued('_fbc'))->toBeNull();
        expect(Cookie::queued('_fbp'))->toBeNull();
    });
});

describe('sync mode', function (): void {
    beforeEach(function (): void {
        config()->set('pixelify.queued_listener_mode', false);
        Event::fake();
    });

    test('it can track page view event', function (): void {
        Pixelify::pageView(new UserData(firstName: 'John', lastName: 'Doe', email: 'test@example.com'));

        Event::assertDispatched(PixelEventOccurred::class, fn ($event): bool => $event->eventData->eventName === 'PageView');
    });

    test('it can track view content event', function (): void {
        Pixelify::viewContent(new ProductData(productId: '123', price: 99.99));

        Event::assertDispatched(PixelEventOccurred::class, fn ($event): bool => $event->eventData->eventName === 'ViewContent'
            && $event->eventData->productData->productId === '123');
    });

    test('it can track add to cart event', function (): void {
        Pixelify::addToCart(new ProductData(productId: '123', price: 99.99, quantity: 2));

        Event::assertDispatched(PixelEventOccurred::class, fn ($event): bool => $event->eventData->eventName === 'AddToCart'
            && $event->eventData->productData->quantity === 2);
    });

    test('it can track initiate checkout event', function (): void {
        Pixelify::initiateCheckout(
            new ProductData(productId: '123', price: 99.99),
            new UserData(email: 'test@example.com')
        );

        Event::assertDispatched(PixelEventOccurred::class, fn ($event): bool => $event->eventData->eventName === 'InitiateCheckout'
            && $event->eventData->userData->email === 'test@example.com');
    });

    test('it can track purchase event', function (): void {
        Pixelify::purchase(new ProductData(productId: '123', price: 99.99, currency: 'USD'));

        Event::assertDispatched(PixelEventOccurred::class, fn ($event): bool => $event->eventData->eventName === 'Purchase'
            && $event->eventData->productData->currency === 'USD');
    });

    test('listener is not queued', function (): void {
        $listener = app(SendPixelEvent::class);
        $event = new PixelEventOccurred(new EventData(eventName: 'PageView', eventId: 'test-id'));

        expect($listener->shouldQueue($event))->toBeFalse();
    });

    test('middleware sets tracking cookies', function (): void {
        $middleware = new FacebookTrackingMiddleware(false);
        $middleware->handle(Request::create('/?fbclid=test123'), fn ($r) => response('ok'));

        expect(Cookie::queued('_fbc'))->not->toBeNull();
        expect(Cookie::queued('_fbp'))->not->toBeNull();
    });
});

