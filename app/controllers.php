<?php

use LandingPayment\Delivery\Http\CreateOrderController;
use LandingPayment\Delivery\Http\DownloadFileByOrderController;

$app['order.create.controller'] = function() use ($app) {
    return new CreateOrderController(
        $app['order.repository'],
        $app['product.repository'],
        $app['order.payment.httpresponse.factory']
    );
};

$app['order.downloadFile.controller'] = function() use ($app) {
    return new DownloadFileByOrderController(
        $app['download.uc']
    );
};
