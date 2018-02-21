<?php

namespace LandingPayment\Delivery\PaymentGateway\PayU\Http;

use LandingPayment\Delivery\PaymentGateway\PayU\Configuration;
use OpenPayU_Order;
use LandingPayment\Usecase\PaymentConfirmationUC;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PingbackController {

    /**
     * @var PaymentConfirmationUC
     */
    private $confirmationUC;

    public function __construct(Configuration $configuration,
                                PaymentConfirmationUC $confirmationUC) {
        $this->confirmationUC = $confirmationUC;
    }

    public function pingback(Request $request) {
        $this->logPingback($request);

        if (!$request->isMethod(Request::METHOD_POST)) {
            throw new BadRequestHttpException();
        }

        $body = $request->getContent();

        $response = OpenPayU_Order::consumeNotification($body);

        $status = $response->getResponse()->order->status;

        if($status == 'COMPLETED') {
            $responseOrder = $response->getResponse()->order;
            $orderId = $responseOrder->extOrderId;
            $amount = $responseOrder->totalAmount / 100;
            $currency = $responseOrder->currencyCode;

            $this->confirmationUC->markAsPaid($orderId, $amount, $currency);
        }

        return Response::create(); // ok
    }

    private function logPingback(Request $request) {
        $gatewayId = "payu";
        $eventName = "pingback";
        $data = [
            'method' => $request->getMethod(),
            'body' => $request->getContent(),
        ];

        $this->confirmationUC->createEvent($gatewayId, $eventName, $data);
    }
}
