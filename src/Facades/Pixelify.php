<?php

declare(strict_types=1);

namespace Revoltify\Pixelify\Facades;

use Illuminate\Support\Facades\Facade;
use Revoltify\Pixelify\Contracts\PixelifyInterface;
use Revoltify\Pixelify\Contracts\PixelifyProductInterface;
use Revoltify\Pixelify\Contracts\PixelifyUserInterface;
use Revoltify\Pixelify\DTO\ProductData;
use Revoltify\Pixelify\DTO\UserData;

/**
 * @method static void viewContent(ProductData|PixelifyProductInterface $productData, UserData|PixelifyUserInterface|null $userData = null)
 * @method static void pageView(UserData|PixelifyUserInterface|null $userData = null)
 * @method static void addToCart(ProductData|PixelifyProductInterface $productData, UserData|PixelifyUserInterface|null $userData = null)
 * @method static void initiateCheckout(ProductData|PixelifyProductInterface $productData, UserData|PixelifyUserInterface|null $userData = null)
 * @method static void purchase(ProductData|PixelifyProductInterface $productData, UserData|PixelifyUserInterface|null $userData = null)
 *
 * @see \Revoltify\Pixelify\Services\PixelifyService
 */
final class Pixelify extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PixelifyInterface::class;
    }
}
