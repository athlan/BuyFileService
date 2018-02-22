<?php

namespace LandingPayment\Domain\Product;

interface ProductRepository {

    /**
     * @param string $productId
     * @return Product|null
     */
    public function getById($productId);
}
