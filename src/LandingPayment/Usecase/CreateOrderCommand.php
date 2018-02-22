<?php

namespace LandingPayment\Usecase;

class CreateOrderCommand
{
    public $productId;
    public $userIp;
    public $email;
    public $invoiceRequested;
    public $invoiceTitle;
    public $invoiceAddress;
    public $invoiceNip;
}
