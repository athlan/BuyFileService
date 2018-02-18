<?php

namespace LandingPayment\Domain;

interface OrderRepository {

    /**
     * @param string $orderId
     * @return Order|null
     */
    public function getById($orderId);
}
