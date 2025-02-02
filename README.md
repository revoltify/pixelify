# Pixelify - Facebook Conversion API Integration for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/revoltify/pixelify.svg?style=flat-square)](https://packagist.org/packages/revoltify/pixelify)
[![Total Downloads](https://img.shields.io/packagist/dt/revoltify/pixelify.svg?style=flat-square)](https://packagist.org/packages/revoltify/pixelify)

A Laravel package for easy integration with Facebook Conversion API to track various events like ViewContent, PageView, AddToCart, InitiateCheckout, and Purchase.

## Installation

You can install the package via composer:

```bash
composer require revoltify/pixelify
```

## Configuration

Publish the configuration file:

```bash
php artisan pixelify:setup
```

Add your Facebook Pixel credentials to your `.env` file:

```env
FACEBOOK_PIXEL_ID=your_pixel_id
FACEBOOK_CONVERSION_API_TOKEN=your_api_token
FACEBOOK_TEST_EVENT_CODE=your_test_event_code (optional)
FACEBOOK_PIXEL_DEBUG=false
```


#### Getting Your Facebook Pixel ID and Access Token

1. Go to [Facebook Events Manager](https://business.facebook.com/events_manager)
2. Select your Pixel
3. Go to the "Settings" tab
4. Copy the Dataset ID (this is your Pixel ID)
5. Scroll down to "Set up direct integration"
6. Click "Generate access token"
7. Copy the generated Pixel Access Token

## Usage

### Model Integration

Implement the interfaces in your models:

```php
use Revoltify\Pixelify\Contracts\PixelifyUserInterface;
use Revoltify\Pixelify\Traits\HasPixelifyUser;

class User extends Model implements PixelifyUserInterface
{
    use HasPixelifyUser;

    // overwrite methods if needed
    public function getPixelFirstName(): ?string
    {
        return $this->name;
    }
}

use Revoltify\Pixelify\Contracts\PixelifyProductInterface;
use Revoltify\Pixelify\Traits\HasPixelifyProduct;

class Product extends Model implements PixelifyProductInterface
{
    use HasPixelifyProduct;

    // overwrite methods if needed
    public function getPixelProductCurrency(): string
    {
        return $this->product_currency;
    }
}
```

### Tracking Events

```php
use Revoltify\Pixelify\Facades\Pixelify;

// Track page view
Pixelify::pageView($user->toPixelUser());

// Track view content
Pixelify::viewContent(
    $product->toPixelProduct(),
    $user->toPixelUser()
);

// Track add to cart
Pixelify::addToCart(
    $product->toPixelProduct(),
    $user->toPixelUser()
);

// Track initiate checkout
Pixelify::initiateCheckout(
    $product->toPixelProduct(),
    $user->toPixelUser()
);

// Track purchase
Pixelify::purchase(
    $product->toPixelProduct(),
    $user->toPixelUser()
);
```

### Using DTOs Directly

You can also create DTOs manually:

```php
use Revoltify\Pixelify\DTO\UserData;
use Revoltify\Pixelify\DTO\ProductData;

$userData = new UserData(
    firstName: 'John',
    lastName: 'Doe'
    email: 'user@example.com',
    phone: '+1234567890',
);

$productData = new ProductData(
    productId: '123',
    price: 99.99,
    quantity: 1,
    currency: 'USD'
);

Pixelify::purchase($productData, $userData);
```

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.