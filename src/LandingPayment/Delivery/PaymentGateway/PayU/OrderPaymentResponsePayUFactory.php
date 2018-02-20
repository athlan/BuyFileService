<?php

namespace LandingPayment\Delivery\PaymentGateway\PayU;

use LandingPayment\Domain\ProductRepository;
use OpenPayU_Configuration;
use OpenPayU_Order;
use LandingPayment\Delivery\PaymentGateway\OrderPaymentResponseFactory;
use LandingPayment\Domain\Order;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class OrderPaymentResponsePayUFactory implements OrderPaymentResponseFactory
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(Configuration $configuration,
                                ProductRepository $productRepository) {
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
        $params['continueUrl'] = 'http://localhost/';

        $params['notifyUrl'] = 'http://localhost/';

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
