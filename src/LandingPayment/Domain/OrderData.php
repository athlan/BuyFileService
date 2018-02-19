<?php

namespace LandingPayment\Domain;


class OrderData
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var bool
     */
    private $invoice;

    /**
     * @var OrderInvoiceData
     */
    private $invoiceData;

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setInvoiceRequest(OrderInvoiceData $invoiceData) {
        $this->invoice = true;
        $this->invoiceData = $invoiceData;
    }

    public function setInvoiceNotRequested() {
        $this->invoice = false;
        $this->invoiceData = null;
    }
}
