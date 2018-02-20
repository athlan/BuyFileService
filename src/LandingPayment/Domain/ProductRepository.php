<?php

namespace LandingPayment\Domain;

interface ProductRepository {

    /**
     * @param string $productId
     * @return Product|null
     */
    public function getById($productId);
}
