<?php

namespace LandingPayment\Usecase;

use LandingPayment\Domain\Order;
use LandingPayment\Domain\OrderInvoiceData;
use LandingPayment\Domain\OrderItem;
use LandingPayment\Domain\OrderRepository;
use LandingPayment\Domain\ProductNotExistsException;
use LandingPayment\Domain\ProductRepository;

class CreateOrderUC
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(OrderRepository $orderRepository,
                                ProductRepository $productRepository) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @param CreateOrderCommand $command
     * @throws ProductNotExistsException
     * @return Order
     */
    public function createOrder(CreateOrderCommand $command) {
        $product = $this->productRepository->getById($command->productId);

        if ($product === null) {
            throw new ProductNotExistsException($command->productId);
        }

        $now = new \DateTimeImmutable();

        $order = Order::createNewOrder($now);
        $order->addItem(OrderItem::newOrderItemFromProduct($product));

        $orderData = $order->getOrderData();

        $orderData->setCreationIp($command->userIp);
        $orderData->setEmail($command->email);

        if ($command->invoiceRequested) {
            $orderData->setInvoiceRequest(
                new OrderInvoiceData(
                    $command->invoiceTitle,
                    $command->invoiceAddress,
                    $command->invoiceNip
                )
            );
        }
        else {
            $orderData->setInvoiceNotRequested();
        }

        $this->orderRepository->save($order);

        return $order;
    }
}
