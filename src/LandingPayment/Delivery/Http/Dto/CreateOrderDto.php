<?php

namespace LandingPayment\Delivery\Http\Dto;

class CreateOrderDto
{
    public $productId;
    public $email;
    public $invoiceRequested;
    public $invoiceTitle;
    public $invoiceAddress;
    public $invoiceNip;
}
