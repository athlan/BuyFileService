<?php

namespace LandingPayment\Domain\Order;

interface OrderRepository {

    /**
     * @param string $orderId
     * @return Order|null
     */
    public function getById($orderId);

    public function save(Order $order);
}
