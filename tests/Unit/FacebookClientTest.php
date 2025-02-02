<?php

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Revoltify\Pixelify\DTO\EventData;
use Revoltify\Pixelify\DTO\ProductData;
use Revoltify\Pixelify\DTO\UserData;
use Revoltify\Pixelify\Http\Client\FacebookClient;

beforeEach(function () {
    config()->set('pixelify.pixel_id', 'test_pixel_id');
    config()->set('pixelify.access_token', 'test_access_token');
    config()->set('pixelify.api_version', 'v18.0');
});

test('it sends event data to Facebook API', function () {
    Http::fake([
        '*' => Http::response([
            'events_received' => 1,
            'success' => true,
            'fbtrace_id' => 'test_trace_123',
        ], 200),
    ]);

    $client = new FacebookClient;
    $eventData = new EventData(
        eventName: 'ViewContent',
        eventId: 'test-123',
        productData: new ProductData(
            productId: '123',
            price: 99.99
        )
    );

    $response = $client->sendEvent($eventData);

    Http::assertSent(function (Request $request) {
        return $request->hasHeader('Content-Type', 'application/json')
            && str_contains($request->url(), 'graph.facebook.com/v18.0/')
            && str_contains($request->url(), '/events');
    });

    expect($response)
        ->toBeArray()
        ->toHaveKey('events_received')
        ->toHaveKey('success')
        ->toHaveKey('fbtrace_id');
});

test('it includes test event code when configured', function () {
    Http::fake([
        '*' => Http::response([
            'events_received' => 1,
            'success' => true,
        ], 200),
    ]);

    config()->set('pixelify.test_event_code', 'TEST123');

    $client = new FacebookClient;
    $eventData = new EventData(
        eventName: 'PageView',
        eventId: 'test-123'
    );

    $response = $client->sendEvent($eventData);

    Http::assertSent(function (Request $request) {
        $data = json_decode($request->body(), true);

        return isset($data['test_event_code'])
            && $data['test_event_code'] === 'TEST123';
    });

    expect($response)
        ->toBeArray()
        ->toHaveKey('events_received')
        ->toHaveKey('success');
});

test('it sends complete user data when provided', function () {
    Http::fake([
        '*' => Http::response([
            'events_received' => 1,
            'success' => true,
        ], 200),
    ]);

    $client = new FacebookClient;
    $eventData = new EventData(
        eventName: 'Purchase',
        eventId: 'test-123',
        userData: new UserData(
            email: 'test@example.com',
            phone: '+1234567890',
            firstName: 'John',
            lastName: 'Doe',
            city: 'New York',
            country: 'US',
            zipCode: '10001'
        )
    );

    $client->sendEvent($eventData);

    Http::assertSent(function (Request $request) {
        $data = json_decode($request->body(), true);
        $userData = $data['data'][0]['user_data'] ?? null;

        return $userData !== null
            && isset($userData['em'])
            && isset($userData['ph'])
            && isset($userData['fn'])
            && isset($userData['ln'])
            && isset($userData['ct'])
            && isset($userData['country'])
            && isset($userData['zp']);
    });
});

test('it sends event with debug mode enabled', function () {
    Http::fake([
        '*' => Http::response([
            'events_received' => 1,
            'success' => true,
        ], 200),
    ]);

    config()->set('pixelify.debug', true);

    $client = new FacebookClient;
    $eventData = new EventData(
        eventName: 'PageView',
        eventId: 'test-123'
    );

    $response = $client->sendEvent($eventData);

    expect($response)
        ->toBeArray()
        ->toHaveKey('events_received')
        ->toHaveKey('success');
});

test('it handles API error responses', function () {
    Http::fake([
        '*' => Http::response([
            'error' => [
                'message' => 'Invalid OAuth access token',
                'type' => 'OAuthException',
                'code' => 190,
            ],
        ], 400),
    ]);

    $client = new FacebookClient;
    $eventData = new EventData(
        eventName: 'PageView',
        eventId: 'test-123'
    );

    $response = $client->sendEvent($eventData);

    expect($response)
        ->toBeArray()
        ->toHaveKey('error')
        ->and($response['error'])
        ->toHaveKey('message')
        ->toHaveKey('type')
        ->toHaveKey('code');
});

test('it sends correct event time', function () {
    Http::fake([
        '*' => Http::response([
            'events_received' => 1,
            'success' => true,
        ], 200),
    ]);

    $client = new FacebookClient;
    $eventData = new EventData(
        eventName: 'PageView',
        eventId: 'test-123'
    );

    $client->sendEvent($eventData);

    Http::assertSent(function (Request $request) {
        $data = json_decode($request->body(), true);

        return isset($data['data'][0]['event_time'])
            && is_int($data['data'][0]['event_time'])
            && $data['data'][0]['event_time'] > 0;
    });
});

test('it sends correct custom data for products', function () {
    Http::fake([
        '*' => Http::response([
            'events_received' => 1,
            'success' => true,
        ], 200),
    ]);

    $client = new FacebookClient;
    $eventData = new EventData(
        eventName: 'ViewContent',
        eventId: 'test-123',
        productData: new ProductData(
            productId: '123',
            price: 99.99,
            quantity: 2,
            currency: 'USD'
        )
    );

    $client->sendEvent($eventData);

    Http::assertSent(function (Request $request) {
        $data = json_decode($request->body(), true);
        $customData = $data['data'][0]['custom_data'] ?? null;

        return $customData !== null
            && $customData['content_ids'] === ['123']
            && $customData['value'] === 99.99
            && $customData['num_items'] === 2
            && $customData['currency'] === 'USD';
    });
});

test('it uses correct API version from config', function () {
    Http::fake([
        '*' => Http::response([
            'events_received' => 1,
            'success' => true,
        ], 200),
    ]);

    config()->set('pixelify.api_version', 'v17.0');

    $client = new FacebookClient;
    $eventData = new EventData(
        eventName: 'PageView',
        eventId: 'test-123'
    );

    $client->sendEvent($eventData);

    Http::assertSent(function (Request $request) {
        return str_contains($request->url(), 'graph.facebook.com/v17.0/');
    });
});
