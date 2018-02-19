<?php

namespace LandingPayment\Usecase;

use InvalidArgumentException;
use LandingPayment\Domain\Download;
use LandingPayment\Domain\DownloadRepository;
use LandingPayment\Domain\OrderRepository;

class DownloadContentUC
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var DownloadRepository
     */
    private $downloadRepository;

    public function __construct(OrderRepository $orderRepository,
                                DownloadRepository $downloadRepository) {
        $this->orderRepository = $orderRepository;
        $this->downloadRepository = $downloadRepository;
    }

    public function canDownloadContent($orderId) {
        $order = $this->orderRepository->getById($orderId);

        if ($order == null) {
            return false;
        }

        return $order->isPaid();
    }

    public function doDownload($orderId, $ip) {
        $order = $this->orderRepository->getById($orderId);

        if ($order == null) {
            throw new InvalidArgumentException();
        }

        $now = new \DateTimeImmutable();

        $download = new Download($order, $now, $ip);
        $this->downloadRepository->save($download);
    }
}
