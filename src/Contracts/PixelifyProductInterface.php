<?php

namespace Revoltify\Pixelify\Contracts;

interface PixelifyProductInterface
{
    public function getPixelProductId(): string;

    public function getPixelProductPrice(): float;

    public function getPixelProductQuantity(): int;

    public function getPixelProductCurrency(): string;
}
