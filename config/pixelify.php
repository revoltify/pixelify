<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Facebook Pixel ID
    |--------------------------------------------------------------------------
    |
    | Your Facebook Pixel ID from Facebook Events Manager
    |
    */
    'pixel_id' => env('FACEBOOK_PIXEL_ID'),

    /*
    |--------------------------------------------------------------------------
    | Facebook Conversion API Access Token
    |--------------------------------------------------------------------------
    |
    | Your Facebook Conversion API Access Token
    |
    */
    'access_token' => env('FACEBOOK_CONVERSION_API_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | API Version
    |--------------------------------------------------------------------------
    |
    | Facebook Graph API version to use
    |
    */
    'api_version' => 'v18.0',

    /*
    |--------------------------------------------------------------------------
    | Test Event Code
    |--------------------------------------------------------------------------
    |
    | Used for testing events in Facebook Events Manager
    |
    */
    'test_event_code' => env('FACEBOOK_TEST_EVENT_CODE'),

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | Enable debug mode to log API responses
    |
    */
    'debug' => env('FACEBOOK_PIXEL_DEBUG', false),
];
