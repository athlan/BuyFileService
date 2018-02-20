<?php

namespace LandingPayment\Usecase;

use InvalidArgumentException;
use LandingPayment\Domain\OrderRepository;
use LandingPayment\Domain\PaymentGatewayEvent;
use LandingPayment\Domain\PaymentGatewayEventRepository;

class PaymentConfirmationUC
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var PaymentGatewayEventRepository
     */
    private $paymentGatewayEventRepository;

    public function __construct(OrderRepository $orderRepository,
                                PaymentGatewayEventRepository $paymentGatewayEventRepository) {
        $this->orderRepository = $orderRepository;
        $this->paymentGatewayEventRepository = $paymentGatewayEventRepository;
    }

    public function markAsPaid($orderId) {
        $now = new \DateTimeImmutable();

        $order = $this->orderRepository->getById($orderId);

        if ($order == null) {
            throw new InvalidArgumentException();
        }

        $order->markAsPaid($now);

        $this->orderRepository->save($order);
    }

    /**
     * @param $gatewayId
     * @param $eventName
     * @param array $data
     */
    public function createEvent($gatewayId, $eventName, array $data) {
        $now = new \DateTimeImmutable();

        $event = new PaymentGatewayEvent(
            $gatewayId,
            $eventName,
            $now,
            $data
        );

        $order = $this->paymentGatewayEventRepository->save($event);
    }
}
