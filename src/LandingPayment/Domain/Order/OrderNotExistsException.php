<?php

namespace LandingPayment\Domain\Order;

use Exception;

class OrderNotExistsException extends Exception {

    /**
     * @var string
     */
    private $orderId;

    /**
     * OrderNotExistsException constructor.
     * @param string $orderId
     */
    public function __construct($orderId) {
        $this->orderId = $orderId;
    }

    /**
     * @return string
     */
    public function getOrderId() {
        return $this->orderId;
    }
}
