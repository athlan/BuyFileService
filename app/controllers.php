<?php

use LandingPayment\Delivery\Http\DownloadFileByOrderController;

$app['order.downloadFile.controller'] = function() use ($app) {
    return new DownloadFileByOrderController(
        $app['download.uc'],
        $app['config.filestream.path']
    );
};
