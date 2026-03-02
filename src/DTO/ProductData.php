<?php

declare(strict_types=1);

namespace Revoltify\Pixelify\DTO;

use Revoltify\Pixelify\Contracts\PixelifyProductInterface;

final class ProductData
{
    public const CONTENT_TYPE = 'product';

    public function __construct(
        public string|array $productId,
        public float $price,
        public int $quantity = 1,
        public string $currency = 'USD',
        public ?string $contentType = self::CONTENT_TYPE,
        public ?array $contents = null
    ) {}

    public static function fromModel(PixelifyProductInterface $model): self
    {
        return new self(
            productId: $model->getPixelProductId(),
            price: $model->getPixelProductPrice(),
            quantity: $model->getPixelProductQuantity(),
            currency: $model->getPixelProductCurrency(),
            contents: [
                [
                    'id' => $model->getPixelProductId(),
                    'quantity' => $model->getPixelProductQuantity(),
                    'price' => $model->getPixelProductPrice(),
                ],
            ]
        );
    }

    public function toArray(): array
    {
        $data = [
            'content_ids' => is_array($this->productId) ? $this->productId : [$this->productId],
            'content_type' => $this->contentType,
            'value' => $this->price,
            'currency' => $this->currency,
            'num_items' => $this->quantity,
        ];

        if ($this->contents) {
            $data['contents'] = array_map(fn (array $item): array => [
                'id' => $item['id'],
                'quantity' => $item['quantity'] ?? 1,
                'item_price' => $item['price'] ?? null,
            ], $this->contents);
        }

        return array_filter($data, fn (float|string|int|array|null $value): bool => $value !== null);
    }
}
