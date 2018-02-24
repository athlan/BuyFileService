<?php

namespace LandingPayment\Usecase;

use InvalidArgumentException;
use LandingPayment\Domain\Order\OrderNotExistsException;
use LandingPayment\Domain\Order\OrderPaidEvent;
use LandingPayment\Domain\Order\OrderRepository;
use LandingPayment\Domain\Payment\PaymentGatewayEvent;
use LandingPayment\Domain\Payment\PaymentGatewayEventRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PaymentConfirmationUC
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var PaymentGatewayEventRepository
     */
    private $paymentGatewayEventRepository;

    public function __construct(EventDispatcherInterface $eventDispatcher,
                                OrderRepository $orderRepository,
                                PaymentGatewayEventRepository $paymentGatewayEventRepository) {
        $this->eventDispatcher = $eventDispatcher;
        $this->orderRepository = $orderRepository;
        $this->paymentGatewayEventRepository = $paymentGatewayEventRepository;
    }

    /**
     * @param string $orderId
     * @param float $amount
     * @param string $currency
     * @throws OrderNotExistsException
     * @return bool
     */
    public function markAsPaid($orderId, $amount, $currency) {
        $now = new \DateTimeImmutable();

        $order = $this->orderRepository->getById($orderId);

        if ($order == null) {
            throw new OrderNotExistsException($orderId);
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

        $event = new OrderPaidEvent($order);
        $this->eventDispatcher->dispatch($event->getName(), $event);

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

        $this->paymentGatewayEventRepository->save($event);
    }
}
