<?php

namespace LandingPayment\Domain;

use Exception;

class ProductNotExistsException extends Exception {

    /**
     * @var string
     */
    private $productId;

    /**
     * ProductNotExistsException constructor.
     * @param string $productId
     */
    public function __construct($productId) {
        $this->productId = $productId;
    }

    /**
     * @return string
     */
    public function getProductId() {
        return $this->productId;
    }
}
