<?php

$app->get('/download/{orderId}', 'order.downloadFile.controller:download');
$app->get('/createOrder/{productId}', 'order.create.controller:createOrder');

$app->post('/payment/gateway/payu/pingback', 'payment.gateway.payu.controller:pingback');
