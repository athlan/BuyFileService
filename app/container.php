<?php

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;

$app = new Application();

require __DIR__ . '/config/params.php';

$app->register(new ServiceControllerServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new HttpFragmentServiceProvider());

$app['twig'] = $app->extend('twig', function ($twig, $app) {
    return $twig;
});

$app->register(new DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'dbname'   => $app['config.db.name'],
        'host'     => $app['config.db.host'],
        'port'     => $app['config.db.port'],
        'user'     => $app['config.db.user'],
        'password' => $app['config.db.password'],
        'charset'  => 'utf8'
    ),
));

$app->register(new DoctrineOrmServiceProvider(), array(
    'orm.proxies_dir' =>  __DIR__ . '/../../var/cache/doctrine-proxy',
    'orm.em.options' => array(
        'mappings' => array(
            array(
                'type' => 'xml',
                'namespace' => 'LandingPayment\Domain',
                'path' => __DIR__ . '/../src/LandingPayment/Infrastructure/Doctrine/mappings',
            ),
        ),
    ),
));

$app['download.uc'] = function () use ($app) {
    return new \LandingPayment\Usecase\DownloadContentUC(
        $app['order.repository'],
        $app['download.repository'],
        $app['product.repository']
    );
};

$app['payment.confirmation.uc'] = function () use ($app) {
    return new \LandingPayment\Usecase\PaymentConfirmationUC(
        $app['order.repository'],
        $app['paymentGatewayEvent.repository']
    );
};

$app['order.repository'] = function () use ($app) {
    return new \LandingPayment\Infrastructure\Doctrine\OrderRepositoryDoctrine($app['orm.em']);
};
$app['download.repository'] = function () use ($app) {
    return new \LandingPayment\Infrastructure\Doctrine\DownloadRepositoryDoctrine($app['orm.em']);
};
$app['product.repository'] = function () use ($app) {
    return new \LandingPayment\Infrastructure\Config\ProductRepositoryConfig($app['product.repository.data']);
};
$app['paymentGatewayEvent.repository'] = function () use ($app) {
    return new \LandingPayment\Infrastructure\Doctrine\PaymentGatewayEventRepositoryDoctrine($app['orm.em']);
};

$app['order.payment.httpresponse.factory'] = function () use ($app) {
    return new \LandingPayment\Delivery\PaymentGateway\PayU\OrderPaymentResponsePayUFactory(
        $app,
        $app['payment.gateway.payu.configuration'],
        $app['product.repository']
    );
};

// payu
$app['payment.gateway.payu.configuration'] = function () use ($app) {
    $config = new \LandingPayment\Delivery\PaymentGateway\PayU\Configuration($app);
    $config->initialize();
    return $config;
};

return $app;
