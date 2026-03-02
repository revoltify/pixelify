<?php

declare(strict_types=1);

namespace Revoltify\Pixelify\Contracts;

use Revoltify\Pixelify\DTO\ProductData;
use Revoltify\Pixelify\DTO\UserData;

interface PixelifyInterface
{
    public function viewContent(ProductData|PixelifyProductInterface $productData, UserData|PixelifyUserInterface|null $userData = null): void;

    public function pageView(UserData|PixelifyUserInterface|null $userData = null): void;

    public function addToCart(ProductData|PixelifyProductInterface $productData, UserData|PixelifyUserInterface|null $userData = null): void;

    public function initiateCheckout(ProductData|PixelifyProductInterface $productData, UserData|PixelifyUserInterface|null $userData = null): void;

    public function purchase(ProductData|PixelifyProductInterface $productData, UserData|PixelifyUserInterface|null $userData = null): void;
}
