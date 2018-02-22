<?php

use LandingPayment\Delivery\Http\CreateOrderController;
use LandingPayment\Delivery\Http\DownloadFileByOrderController;

$app['order.create.controller'] = function() use ($app) {
    return new CreateOrderController(
        $app['order.create.form'],
        $app['order.create.uc'],
        $app['order.payment.httpresponse.factory']
    );
};

$app['order.downloadFile.controller'] = function() use ($app) {
    return new DownloadFileByOrderController(
        $app['download.uc']
    );
};

$app['payment.gateway.payu.controller'] = function() use ($app) {
    return new \LandingPayment\Delivery\PaymentGateway\PayU\Http\PingbackController(
        $app['payment.gateway.payu.configuration'],
        $app['payment.confirmation.uc']
    );
};
