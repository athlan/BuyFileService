<?php

namespace LandingPayment\Domain;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

class OrderItem {

    /**
     * @var string
     */
    private $productId;

    /**
     * @var float
     */
    private $price;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var int
     */
    private $quantity;

    private function __construct($productId, $price, $currency) {
        $this->productId = $productId;
        $this->price = $price;
        $this->currency = $currency;
        $this->quantity = 1;
    }

    /**
     * @param $productId
     * @param $price
     * @param $currency
     * @return OrderItem
     */
    public static function newOrderItem($productId, $price, $currency) {
        return new OrderItem($productId, $price, $currency);
    }

    /**
     * @param Product $product
     * @return OrderItem
     */
    public static function newOrderItemFromProduct(Product $product) {
        return new OrderItem($product->getProductId(), $product->getPrice(), $product->getCurrency());
    }

    /**
     * @return string
     */
    public function getProductId() {
        return $this->productId;
    }

    /**
     * @return float
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * @return string
     */
    public function getCurrency() {
        return $this->currency;
    }

    /**
     * @return int
     */
    public function getQuantity() {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }
}
