<?php

$app->get('/download/{orderId}', 'order.downloadFile.controller:download')
    ->bind('download');

$app->post('/createOrder', 'order.create.controller:createOrder');

$app->post('/payment/gateway/payu/pingback', 'payment.gateway.payu.controller:pingback');
