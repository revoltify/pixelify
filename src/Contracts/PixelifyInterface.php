<?php

namespace Revoltify\Pixelify\Contracts;

use Revoltify\Pixelify\DTO\ProductData;
use Revoltify\Pixelify\DTO\UserData;

interface PixelifyInterface
{
    public function viewContent(ProductData $productData, ?UserData $userData = null): void;

    public function pageView(?UserData $userData = null): void;

    public function addToCart(ProductData $productData, ?UserData $userData = null): void;

    public function initiateCheckout(ProductData $productData, ?UserData $userData = null): void;

    public function purchase(ProductData $productData, ?UserData $userData = null): void;
}
