<?php

declare(strict_types=1);

namespace Revoltify\Pixelify\Traits;

use Revoltify\Pixelify\DTO\ProductData;

trait HasPixelifyProduct
{
    final public function toPixelProduct(): ProductData
    {
        return ProductData::fromModel($this);
    }

    public function getPixelProductId(): string
    {
        return (string) $this->id;
    }

    public function getPixelProductPrice(): float
    {
        return (float) $this->price ?? 0.00;
    }

    public function getPixelProductQuantity(): int
    {
        return (int) $this->quantity ?? 1;
    }

    public function getPixelProductCurrency(): string
    {
        return $this->currency ?? 'USD';
    }
}
