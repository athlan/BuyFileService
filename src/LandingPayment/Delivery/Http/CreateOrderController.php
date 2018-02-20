<?php

namespace LandingPayment\Delivery\Http;

use LandingPayment\Delivery\PaymentGateway\OrderPaymentResponseFactory;
use LandingPayment\Domain\Order;
use LandingPayment\Domain\OrderInvoiceData;
use LandingPayment\Domain\OrderItem;
use LandingPayment\Domain\OrderRepository;
use LandingPayment\Domain\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateOrderController {

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var OrderPaymentResponseFactory
     */
    private $orderPaymentResponseFactory;

    public function __construct(OrderRepository $orderRepository,
                                ProductRepository $productRepository,
                                OrderPaymentResponseFactory $orderPaymentResponseFactory) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->orderPaymentResponseFactory = $orderPaymentResponseFactory;
    }

    public function createOrder(Request $request, $productId) {
        $product = $this->productRepository->getById($productId);

        if ($product == null) {
            throw new NotFoundHttpException();
        }

        $now = new \DateTimeImmutable();

        $order = Order::createNewOrder($now);
        $order->addItem(OrderItem::newOrderItemFromProduct($product));

        $orderData = $order->getOrderData();

        $orderData->setCreationIp($request->getClientIp());
        $orderData->setEmail('a@a.pl');

        //$orderData->setInvoiceNotRequested();
        $orderData->setInvoiceRequest(new OrderInvoiceData('title', 'address', 'nip'));

        $this->orderRepository->save($order);

        return $this->orderPaymentResponseFactory->createPaymentResponse($order);
    }
}
