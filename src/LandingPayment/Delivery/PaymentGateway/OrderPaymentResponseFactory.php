<?php

namespace LandingPayment\Delivery\PaymentGateway;

use LandingPayment\Domain\Order;
use Symfony\Component\HttpFoundation\Response;

interface OrderPaymentResponseFactory
{
    /**
     * @param Order $order
     * @return Response
     */
    public function createPaymentResponse(Order $order);
}
