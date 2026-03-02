<?php

declare(strict_types=1);

namespace Revoltify\Pixelify\Services;

use Illuminate\Support\Str;
use Revoltify\Pixelify\Contracts\PixelifyInterface;
use Revoltify\Pixelify\Contracts\PixelifyProductInterface;
use Revoltify\Pixelify\Contracts\PixelifyUserInterface;
use Revoltify\Pixelify\DTO\EventData;
use Revoltify\Pixelify\DTO\ProductData;
use Revoltify\Pixelify\DTO\UserData;
use Revoltify\Pixelify\Enums\PixelEvent;
use Revoltify\Pixelify\Events\PixelEventOccurred;

final class PixelifyService implements PixelifyInterface
{
    public function viewContent(ProductData|PixelifyProductInterface $productData, UserData|PixelifyUserInterface|null $userData = null): void
    {
        $this->trackEvent(
            event: PixelEvent::VIEW_CONTENT,
            productData: $productData,
            userData: $userData
        );
    }

    public function pageView(UserData|PixelifyUserInterface|null $userData = null): void
    {
        $this->trackEvent(
            event: PixelEvent::PAGE_VIEW,
            userData: $userData
        );
    }

    public function addToCart(ProductData|PixelifyProductInterface $productData, UserData|PixelifyUserInterface|null $userData = null): void
    {
        $this->trackEvent(
            event: PixelEvent::ADD_TO_CART,
            productData: $productData,
            userData: $userData
        );
    }

    public function initiateCheckout(ProductData|PixelifyProductInterface $productData, UserData|PixelifyUserInterface|null $userData = null): void
    {
        $this->trackEvent(
            event: PixelEvent::INITIATE_CHECKOUT,
            productData: $productData,
            userData: $userData
        );
    }

    public function purchase(ProductData|PixelifyProductInterface $productData, UserData|PixelifyUserInterface|null $userData = null): void
    {
        $this->trackEvent(
            event: PixelEvent::PURCHASE,
            productData: $productData,
            userData: $userData
        );
    }

    private function trackEvent(PixelEvent $event, ProductData|PixelifyProductInterface|null $productData = null, UserData|PixelifyUserInterface|null $userData = null): void
    {
        if ($productData instanceof PixelifyProductInterface) {
            $productData = $productData->toPixelProduct();
        }

        if ($userData instanceof PixelifyUserInterface) {
            $userData = $userData->toPixelUser();
        }

        $eventData = new EventData(
            eventName: $event->value,
            eventId: (string) Str::uuid(),
            userData: $userData,
            productData: $productData
        );

        event(new PixelEventOccurred($eventData));
    }
}
