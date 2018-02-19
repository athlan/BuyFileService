<?php

namespace LandingPayment\Domain;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class Order {

    /**
     * @var \Ramsey\Uuid\UuidInterface
     */
    private $orderId;

    /**
     * @var DateTimeImmutable
     */
    private $creationDate;

    /**
     * @var DateTimeImmutable|NULL
     */
    private $paidDate;

    private function __construct(DateTimeImmutable $creationDate) {
        $this->orderId = Uuid::uuid4();
        $this->creationDate = $creationDate;
    }

    /**
     * @param DateTimeImmutable $creationDate
     * @return Order
     */
    public static function createNewOrder(DateTimeImmutable $creationDate) {
        return new Order($creationDate);
    }

    /**
     * @return \Ramsey\Uuid\UuidInterface
     */
    public function getOrderId() {
        return $this->orderId;
    }

    public function markAsPaid(DateTimeImmutable $paidDate) {
        return $this->paidDate = $paidDate;
    }

    /**
     * @return bool
     */
    public function isPaid() {
        return $this->paidDate !== null;
    }
}
