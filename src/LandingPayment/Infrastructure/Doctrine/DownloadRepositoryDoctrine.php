<?php

namespace LandingPayment\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManager;
use LandingPayment\Domain\DownloadRepository;

class DownloadRepositoryDoctrine implements DownloadRepository {

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function save($download) {
        $this->em->persist($download);
        $this->em->flush();
    }
}
