<?php

namespace LandingPayment\Delivery\PaymentGateway\PayU;

use LandingPayment\Delivery\PaymentGateway\ReturnUrlFactory;
use LandingPayment\Domain\Product\ProductRepository;
use OpenPayU_Configuration;
use OpenPayU_Order;
use LandingPayment\Delivery\PaymentGateway\OrderPaymentResponseFactory;
use LandingPayment\Domain\Order\Order;
use Silex\Application;
use Silex\Application\UrlGeneratorTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class OrderPaymentResponsePayUFactory implements OrderPaymentResponseFactory
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct($app,
                                Configuration $configuration,
                                ProductRepository $productRepository) {
        $this->app = $app;
        $this->productRepository = $productRepository;
    }

    /**
     * @param Order $order
     * @return Response
     */
    public function createPaymentResponse(Order $order)
    {
        $orderItem = $order->getItem();

        $product = $this->productRepository->getById($orderItem->getProductId());

        //customer will be redirected to this page after successfull payment
        $params['continueUrl'] = ReturnUrlFactory::create($order, $product);

        $params['notifyUrl'] = $this->app['app.url'] . '/payment/gateway/payu/pingback';

        $params['customerIp'] = $order->getOrderData()->getCreationIp();
        $params['merchantPosId'] = OpenPayU_Configuration::getMerchantPosId();
        $params['description'] = $product->getTitle();
        $params['currencyCode'] = $orderItem->getCurrency();
        $params['totalAmount'] = $orderItem->getPrice() * 100;
        $params['extOrderId'] = $order->getOrderId()->toString();

        $params['products'][0]['name'] = $product->getTitle();
        $params['products'][0]['unitPrice'] = $orderItem->getPrice() * 100;
        $params['products'][0]['quantity'] = $orderItem->getQuantity();

        $params['buyer']['email'] = $order->getOrderData()->getEmail();

        $response = OpenPayU_Order::create($params);
        $redirectUrl = $response->getResponse()->redirectUri;

        return RedirectResponse::create($redirectUrl);
    }
}
