<?php

namespace LandingPayment\Domain\Product;

class Product
{
    /**
     * @var string
     */
    private $productId;

    /**
     * @var string
     */
    private $title;

    /**
     * @var float
     */
    private $price;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var array
     */
    private $metadata;

    /**
     * Product constructor.
     * @param string $productId
     * @param string $title
     * @param float $price
     * @param string $currency
     * @param array $metadata
     */
    public function __construct($productId, $title, $price, $currency, array $metadata)
    {
        $this->productId = $productId;
        $this->title = $title;
        $this->price = $price;
        $this->currency = $currency;
        $this->metadata = $metadata;
    }

    /**
     * @return string
     */
    public function getProductId() {
        return $this->productId;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
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
     * @return array
     */
    public function getMetadata() {
        return $this->metadata;
    }
}
