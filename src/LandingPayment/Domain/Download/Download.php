<?php

namespace LandingPayment\Domain\Download;

use DateTimeImmutable;
use LandingPayment\Domain\Order\Order;

class Download {

    /**
     * @var Order
     */
    private $order;

    /**
     * @var DateTimeImmutable
     */
    private $downloadDate;

    /**
     * @var string
     */
    private $ip;

    public function __construct(Order $order, DateTimeImmutable $downloadDate, $ip) {
        $this->order = $order;
        $this->downloadDate = $downloadDate;
        $this->ip = $ip;
    }
}
