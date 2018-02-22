<?php

namespace LandingPayment\Delivery\PaymentGateway;

use LandingPayment\Domain\Order\Order;
use LandingPayment\Domain\Product\Product;

class ReturnUrlFactory
{
    public static function create(Order $order, Product $product) {
        $url = $product->getMetadata()['payment.return.url'];

        $url = str_replace('{orderId}', $order->getOrderId(), $url);

        return $url;
    }
}
