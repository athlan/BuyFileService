<?php

namespace LandingPayment\Usecase;

use InvalidArgumentException;
use LandingPayment\Domain\Download\Download;
use LandingPayment\Domain\Download\DownloadRepository;
use LandingPayment\Domain\Order\OrderNotExistsException;
use LandingPayment\Domain\Order\OrderRepository;
use LandingPayment\Domain\Product\Product;
use LandingPayment\Domain\Product\ProductNotExistsException;
use LandingPayment\Domain\Product\ProductRepository;

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
     * @throws OrderNotExistsException
     * @throws ProductNotExistsException
     * @return Product
     */
    public function doDownload($orderId, $ip) {
        $order = $this->orderRepository->getById($orderId);

        if ($order == null) {
            throw new OrderNotExistsException($orderId);
        }

        $now = new \DateTimeImmutable();

        $download = new Download($order, $now, $ip);
        $this->downloadRepository->save($download);

        $productId = $order->getItem()->getProductId();
        $product = $this->productRepository->getById($productId);

        if ($product === null) {
            throw new ProductNotExistsException($productId);
        }

        return $product;
    }
}
