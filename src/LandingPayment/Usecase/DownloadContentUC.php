<?php

namespace LandingPayment\Usecase;

use InvalidArgumentException;
use LandingPayment\Domain\Download;
use LandingPayment\Domain\DownloadRepository;
use LandingPayment\Domain\OrderRepository;
use LandingPayment\Domain\Product;
use LandingPayment\Domain\ProductRepository;

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

    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(OrderRepository $orderRepository,
                                DownloadRepository $downloadRepository,
                                ProductRepository $productRepository) {
        $this->orderRepository = $orderRepository;
        $this->downloadRepository = $downloadRepository;
        $this->productRepository = $productRepository;
    }

    public function canDownloadContent($orderId) {
        $order = $this->orderRepository->getById($orderId);

        if ($order == null) {
            return false;
        }

        return $order->isPaid();
    }

    /**
     * @param $orderId
     * @param $ip
     * @return Product
     */
    public function doDownload($orderId, $ip) {
        $order = $this->orderRepository->getById($orderId);

        if ($order == null) {
            throw new InvalidArgumentException();
        }

        $now = new \DateTimeImmutable();

        $download = new Download($order, $now, $ip);
        $this->downloadRepository->save($download);

        $productId = $order->getItem()->getProductId();
        $product = $this->productRepository->getById($productId);

        return $product;
    }
}
