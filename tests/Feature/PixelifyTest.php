<?php

use Illuminate\Support\Facades\Event;
use Revoltify\Pixelify\DTO\ProductData;
use Revoltify\Pixelify\DTO\UserData;
use Revoltify\Pixelify\Events\PixelEventOccurred;
use Revoltify\Pixelify\Facades\Pixelify;

beforeEach(function () {
    Event::fake();
});

test('it can track page view event', function () {
    $userData = new UserData(
        email: 'test@example.com',
        firstName: 'John',
        lastName: 'Doe'
    );

    Pixelify::pageView($userData);

    Event::assertDispatched(PixelEventOccurred::class, function ($event) {
        return $event->eventData->eventName === 'PageView';
    });
});

test('it can track view content event', function () {
    $productData = new ProductData(
        productId: '123',
        price: 99.99,
    );

    Pixelify::viewContent($productData);

    Event::assertDispatched(PixelEventOccurred::class, function ($event) {
        return $event->eventData->eventName === 'ViewContent'
        && $event->eventData->productData->productId === '123';
    });
});

test('it can track add to cart event', function () {
    $productData = new ProductData(
        productId: '123',
        price: 99.99,
        quantity: 2
    );

    Pixelify::addToCart($productData);

    Event::assertDispatched(PixelEventOccurred::class, function ($event) {
        return $event->eventData->eventName === 'AddToCart'
        && $event->eventData->productData->quantity === 2;
    });
});

test('it can track initiate checkout event', function () {
    $productData = new ProductData(
        productId: '123',
        price: 99.99
    );

    $userData = new UserData(
        email: 'test@example.com'
    );

    Pixelify::initiateCheckout($productData, $userData);

    Event::assertDispatched(PixelEventOccurred::class, function ($event) {
        return $event->eventData->eventName === 'InitiateCheckout'
        && $event->eventData->userData->email === 'test@example.com';
    });
});

test('it can track purchase event', function () {
    $productData = new ProductData(
        productId: '123',
        price: 99.99,
        currency: 'USD'
    );

    Pixelify::purchase($productData);

    Event::assertDispatched(PixelEventOccurred::class, function ($event) {
        return $event->eventData->eventName === 'Purchase'
        && $event->eventData->productData->currency === 'USD';
    });
});
