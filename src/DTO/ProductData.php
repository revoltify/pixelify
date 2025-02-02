<?php

namespace Revoltify\Pixelify\DTO;

class ProductData
{
    public const CONTENT_TYPE = 'product';

    public function __construct(
        public string|array $productId,
        public float $price,
        public int $quantity = 1,
        public string $currency = 'USD',
        public ?string $contentType = self::CONTENT_TYPE,
        public ?array $contents = null,
        public ?string $orderId = null,
    ) {}

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
            $data['contents'] = array_map(function ($item) {
                return [
                    'id' => $item['id'],
                    'quantity' => $item['quantity'] ?? 1,
                    'item_price' => $item['price'] ?? null,
                ];
            }, $this->contents);
        }

        if ($this->orderId) {
            $data['order_id'] = $this->orderId;
        }

        return array_filter($data, fn ($value) => $value !== null);
    }

    public static function fromModel($model): self
    {
        if (! $model instanceof \Revoltify\Pixelify\Contracts\PixelifyProductInterface) {
            throw new \InvalidArgumentException('Model must implement PixelifyProductInterface');
        }

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
}
