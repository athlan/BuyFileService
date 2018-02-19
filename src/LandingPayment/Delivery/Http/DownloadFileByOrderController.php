<?php

namespace LandingPayment\Delivery\Http;

use LandingPayment\Domain\OrderRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DownloadFileByOrderController {

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository) {
        $this->orderRepository = $orderRepository;
    }

    public function download($orderId) {
        $order = $this->orderRepository->getById($orderId);

        if ($order == null) {
            throw new NotFoundHttpException();
        }

        return;
    }
}
