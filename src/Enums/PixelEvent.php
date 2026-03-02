<?php

declare(strict_types=1);

namespace Revoltify\Pixelify\Enums;

enum PixelEvent: string
{
    case VIEW_CONTENT = 'ViewContent';
    case PAGE_VIEW = 'PageView';
    case ADD_TO_CART = 'AddToCart';
    case INITIATE_CHECKOUT = 'InitiateCheckout';
    case PURCHASE = 'Purchase';
}
