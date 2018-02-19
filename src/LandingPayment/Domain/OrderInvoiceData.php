<?php

namespace LandingPayment\Domain;


class OrderInvoiceData
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $nip;

    /**
     * OrderInvoiceData constructor.
     * @param string $title
     * @param string $address
     * @param string $nip
     */
    public function __construct($title, $address, $nip) {
        $this->title = $title;
        $this->address = $address;
        $this->nip = $nip;
    }
}
