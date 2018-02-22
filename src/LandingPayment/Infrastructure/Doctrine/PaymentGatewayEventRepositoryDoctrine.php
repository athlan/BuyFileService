<?php

namespace LandingPayment\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManager;
use LandingPayment\Domain\Payment\PaymentGatewayEvent;
use LandingPayment\Domain\Payment\PaymentGatewayEventRepository;

class PaymentGatewayEventRepositoryDoctrine implements PaymentGatewayEventRepository {

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function save(PaymentGatewayEvent $event) {
        $this->em->persist($event);
        $this->em->flush();
    }
}
