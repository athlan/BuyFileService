<?php

namespace LandingPayment\Delivery\PaymentGateway\PayU;

use Silex\Application;
use OpenPayU_Configuration;

class Configuration
{
    private $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }

    public function initialize() {
        $app = $this->app;

        //set Production Environment
        OpenPayU_Configuration::setEnvironment($app['payment.gateway.payu']['environment']);

        //set POS ID and Second MD5 Key (from merchant admin panel)
        OpenPayU_Configuration::setMerchantPosId($app['payment.gateway.payu']['merchantPosId']);
        OpenPayU_Configuration::setSignatureKey($app['payment.gateway.payu']['signatureKey']);

        //set Oauth Client Id and Oauth Client Secret (from merchant admin panel)
        OpenPayU_Configuration::setOauthClientId($app['payment.gateway.payu']['oauthClientId']);
        OpenPayU_Configuration::setOauthClientSecret($app['payment.gateway.payu']['oauthClientSecret']);
    }
}
