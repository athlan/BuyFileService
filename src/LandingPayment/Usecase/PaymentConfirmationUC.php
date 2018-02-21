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

    /**
     * @param string $orderId
     * @param float $amount
     * @param string $currency
     * @return bool
     */
    public function markAsPaid($orderId, $amount, $currency) {
        $now = new \DateTimeImmutable();

        $order = $this->orderRepository->getById($orderId);

        if ($order == null) {
            throw new InvalidArgumentException();
        }

        $orderItem = $order->getItem();

        if ($currency !== $orderItem->getCurrency()) {
            return false;
        }
        if ($amount < $orderItem->getPrice()) {
            return false;
        }

        $order->markAsPaid($now);

        $this->orderRepository->save($order);

        return true;
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
