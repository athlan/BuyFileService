<?php

use LandingPayment\Delivery\Http\DownloadFileByOrderController;

$app['order.downloadFile.controller'] = function() use ($app) {
    return new DownloadFileByOrderController($app['order.repository']);
};
