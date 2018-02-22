<?php

namespace LandingPayment\Domain\Payment;

interface PaymentGatewayEventRepository {

    /**
     * @param PaymentGatewayEvent $event
     */
    public function save(PaymentGatewayEvent $event);
}
