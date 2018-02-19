<?php

use LandingPayment\Delivery\Http\CreateOrderController;
use LandingPayment\Delivery\Http\DownloadFileByOrderController;

$app['order.create.controller'] = function() use ($app) {
    return new CreateOrderController(
        $app['order.repository']
    );
};

$app['order.downloadFile.controller'] = function() use ($app) {
    return new DownloadFileByOrderController(
        $app['download.uc'],
        $app['config.filestream.path']
    );
};
