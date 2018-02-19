<?php

$app->get('/download/{orderId}', 'order.downloadFile.controller:download');
