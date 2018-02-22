<?php

namespace LandingPayment\Usecase;

use LandingPayment\Domain\Order\Order;
use LandingPayment\Domain\Order\OrderInvoiceData;
use LandingPayment\Domain\Order\OrderItem;
use LandingPayment\Domain\Order\OrderRepository;
use LandingPayment\Domain\Product\ProductNotExistsException;
use LandingPayment\Domain\Product\ProductRepository;

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
