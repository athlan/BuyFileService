<?php

use Silex\Application;

$app = new Application();

require __DIR__ . '/config/params.php';

$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\HttpFragmentServiceProvider());
$app->register(new Silex\Provider\HttpFragmentServiceProvider());
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(),
));
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../templates',
));

$app['twig'] = $app->extend('twig', function ($twig, $app) {
    return $twig;
});

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
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

$app->register(new Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider(), array(
    'orm.proxies_dir' =>  __DIR__ . '/../var/cache/doctrine-proxy',
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

$app['eventbus'] = function () use ($app) {
    return new \Symfony\Component\EventDispatcher\EventDispatcher();
};

// usecases

$app['order.create.uc'] = function () use ($app) {
    return new \LandingPayment\Usecase\CreateOrderUC(
        $app['order.repository'],
        $app['product.repository']
    );
};

$app['download.uc'] = function () use ($app) {
    return new \LandingPayment\Usecase\DownloadContentUC(
        $app['order.repository'],
        $app['download.repository'],
        $app['product.repository']
    );
};

$app['payment.confirmation.uc'] = function () use ($app) {
    return new \LandingPayment\Usecase\PaymentConfirmationUC(
        $app['eventbus'],
        $app['order.repository'],
        $app['paymentGatewayEvent.repository']
    );
};

// repositories

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

// misc
$app['mailer.factory'] = function () use ($app) {
    return new \LandingPayment\Infrastructure\Mail\MailerFactory($app['product.repository']);
};

// event handler
$app['payment.confirmation.eventhandler.orderpaid'] = function () use ($app) {
    return new \LandingPayment\Infrastructure\Mail\OrderPaidEventListener(
        $app['product.repository'],
        $app['mailer.factory'],
        $app['twig']
    );
};

// payu
$app['payment.gateway.payu.configuration'] = function () use ($app) {
    $config = new \LandingPayment\Delivery\PaymentGateway\PayU\Configuration($app);
    $config->initialize();
    return $config;
};

$app['order.create.form'] = function () use ($app) {
    return new \LandingPayment\Delivery\Http\Dto\CreateOrderFormFactory($app['form.factory']);
};

return $app;
