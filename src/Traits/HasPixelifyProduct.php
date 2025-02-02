<?php

namespace Revoltify\Pixelify\Traits;

use Revoltify\Pixelify\DTO\ProductData;

trait HasPixelifyProduct
{
    public function toPixelProduct(): ProductData
    {
        return ProductData::fromModel($this);
    }

    public function getPixelProductId(): string
    {
        return (string) $this->id;
    }

    public function getPixelProductPrice(): float
    {
        return (float) $this->price ?? 0;
    }

    public function getPixelProductQuantity(): int
    {
        return 1;
    }

    public function getPixelProductCurrency(): string
    {
        return $this->currency ?? 'BDT';
    }
}
