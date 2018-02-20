<?php

namespace LandingPayment\Infrastructure\Config;

use LandingPayment\Domain\Product;
use LandingPayment\Domain\ProductRepository;

class ProductRepositoryConfig implements ProductRepository
{
    private $products = [];

    public function __construct(array $products) {
        $this->products = $products;
    }

    /**
     * @param string $productId
     * @return Product|null
     */
    public function getById($productId) {
        if(!array_key_exists($productId, $this->products)) {
            return null;
        }

        $product = $this->products[$productId];

        return new Product(
            $productId,
            $product['title'],
            $product['price'],
            $product['currency'],
            $product['metadata']
        );
    }
}
