<?php

namespace Revoltify\Pixelify\Services;

use Illuminate\Support\Str;
use Revoltify\Pixelify\Contracts\PixelifyInterface;
use Revoltify\Pixelify\DTO\EventData;
use Revoltify\Pixelify\DTO\ProductData;
use Revoltify\Pixelify\DTO\UserData;
use Revoltify\Pixelify\Events\PixelEventOccurred;

class PixelifyService implements PixelifyInterface
{
    public function viewContent(ProductData $productData, ?UserData $userData = null): void
    {
        $this->trackEvent('ViewContent', $userData, $productData);
    }

    public function pageView(?UserData $userData = null): void
    {
        $this->trackEvent('PageView', $userData);
    }

    public function addToCart(ProductData $productData, ?UserData $userData = null): void
    {
        $this->trackEvent('AddToCart', $userData, $productData);
    }

    public function initiateCheckout(ProductData $productData, ?UserData $userData = null): void
    {
        $this->trackEvent('InitiateCheckout', $userData, $productData);
    }

    public function purchase(ProductData $productData, ?UserData $userData = null): void
    {
        $this->trackEvent('Purchase', $userData, $productData);
    }

    private function trackEvent(string $eventName, ?UserData $userData = null, ?ProductData $productData = null): void
    {
        $eventData = new EventData(
            eventName: $eventName,
            eventId: (string) Str::uuid(),
            userData: $userData,
            productData: $productData
        );

        event(new PixelEventOccurred($eventData));
    }
}
