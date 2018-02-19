<?php

namespace LandingPayment\Delivery\Http;

use LandingPayment\Domain\Order;
use LandingPayment\Domain\OrderInvoiceData;
use LandingPayment\Domain\OrderRepository;
use LandingPayment\Usecase\DownloadContentUC;

class CreateOrderController {

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository) {
        $this->orderRepository = $orderRepository;
    }

    public function createOrder() {
        $now = new \DateTimeImmutable();

        $order = Order::createNewOrder($now);
        $orderData = $order->getOrderData();

        $orderData->setEmail('a@a.pl');

        //$orderData->setInvoiceNotRequested();
        $orderData->setInvoiceRequest(new OrderInvoiceData('title', 'address', 'nip'));

        $this->orderRepository->save($order);

        // TODO return sth
    }
}
