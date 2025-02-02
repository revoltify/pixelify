<?php

namespace Revoltify\Pixelify\Facades;

use Illuminate\Support\Facades\Facade;
use Revoltify\Pixelify\Contracts\PixelifyInterface;
use Revoltify\Pixelify\DTO\ProductData;
use Revoltify\Pixelify\DTO\UserData;

/**
 * @method static void viewContent(ProductData $productData, ?UserData $userData = null)
 * @method static void pageView(?UserData $userData = null)
 * @method static void addToCart(ProductData $productData, ?UserData $userData = null)
 * @method static void initiateCheckout(ProductData $productData, ?UserData $userData = null)
 * @method static void purchase(ProductData $productData, ?UserData $userData = null)
 */
class Pixelify extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PixelifyInterface::class;
    }
}
