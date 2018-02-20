<?php

namespace LandingPayment\Domain;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class PaymentGatewayEvent
{
    /**
     * @var UuidInterface
     */
    private $eventId;

    /**
     * @var string
     */
    private $gatewayId;

    /**
     * @var string
     */
    private $eventName;

    /**
     * @var \DateTimeImmutable
     */
    private $date;

    /**
     * @var array
     */
    private $data;

    /**
     * PaymentGatewayEvent constructor.
     * @param string $gatewayId
     * @param string $eventName
     * @param \DateTimeImmutable $date
     * @param array $data
     */
    public function __construct($gatewayId, $eventName, \DateTimeImmutable $date, array $data)
    {
        $this->eventId = Uuid::uuid4();
        $this->gatewayId = $gatewayId;
        $this->eventName = $eventName;
        $this->date = $date;
        $this->data = $data;
    }
}
