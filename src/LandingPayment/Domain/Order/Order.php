<?php

namespace LandingPayment\Domain\Order;

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
     * @var OrderData
     */
    private $orderData;

    /**
     * @var DateTimeImmutable|NULL
     */
    private $paidDate;

    /**
     * @var OrderItem
     */
    private $orderItem;

    private function __construct(DateTimeImmutable $creationDate) {
        $this->orderId = Uuid::uuid4();
        $this->creationDate = $creationDate;
        $this->orderData = new OrderData();
    }

    /**
     * @param DateTimeImmutable $creationDate
     * @return Order
     */
    public static function createNewOrder(DateTimeImmutable $creationDate) {
        return new Order($creationDate);
    }

    /**
     * @param OrderItem $item
     */
    public function addItem(OrderItem $item) {
        $this->orderItem = $item;
    }

    /**
     * @return OrderItem
     */
    public function getItem() {
        return $this->orderItem;
    }

    /**
     * @return OrderData
     */
    public function getOrderData() {
        return $this->orderData;
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
