<?php

ini_set('display_errors', 0);

require_once __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../app/container.php';

require __DIR__ . '/../app/controllers.php';
require __DIR__ . '/../app/router.php';

$app->run();
