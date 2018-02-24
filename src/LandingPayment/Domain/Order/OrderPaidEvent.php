<?php

namespace LandingPayment\Domain\Order;

use Symfony\Component\EventDispatcher\Event;

class OrderPaidEvent extends Event {

    const NAME = OrderPaidEvent::class;

    /**
     * @var Order
     */
    private $order;

    public function __construct(Order $order) {
        $this->order = $order;
    }

    /**
     * @return string
     */
    public function getName() {
        return self::NAME;
    }

    /**
     * @return Order
     */
    public function getOrder() {
        return $this->order;
    }
}
