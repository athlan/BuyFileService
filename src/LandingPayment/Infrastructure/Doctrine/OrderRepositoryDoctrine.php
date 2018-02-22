<?php

namespace LandingPayment\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManager;
use LandingPayment\Domain\Order\Order;
use LandingPayment\Domain\Order\OrderRepository;

class OrderRepositoryDoctrine implements OrderRepository {

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function getById($orderId) {
        return $this->em->find(Order::class, $orderId);
    }

    public function save(Order $order) {
        $this->em->persist($order);
        $this->em->flush();
    }
}
