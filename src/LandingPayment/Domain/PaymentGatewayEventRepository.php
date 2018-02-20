<?php

namespace LandingPayment\Domain;

interface PaymentGatewayEventRepository {

    /**
     * @param PaymentGatewayEvent $event
     */
    public function save(PaymentGatewayEvent $event);
}
