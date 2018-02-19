<?php

$app->get('/download/{orderId}', 'order.downloadFile.controller:download');
$app->get('/createOrder', 'order.create.controller:createOrder');
