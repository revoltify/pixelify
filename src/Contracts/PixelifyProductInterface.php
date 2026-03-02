<?php

declare(strict_types=1);

namespace Revoltify\Pixelify\Contracts;

use Revoltify\Pixelify\DTO\ProductData;

interface PixelifyProductInterface
{
    public function toPixelProduct(): ProductData;

    public function getPixelProductId(): string;

    public function getPixelProductPrice(): float;

    public function getPixelProductQuantity(): int;

    public function getPixelProductCurrency(): string;
}
