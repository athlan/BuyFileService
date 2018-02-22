<?php

namespace LandingPayment\Delivery\Http;

use LandingPayment\Delivery\Http\Dto\CreateOrderDto;
use LandingPayment\Delivery\Http\Dto\CreateOrderFormFactory;
use LandingPayment\Delivery\Http\Response\FormErrorResponseBuilder;
use LandingPayment\Delivery\PaymentGateway\OrderPaymentResponseFactory;
use LandingPayment\Domain\Product\ProductNotExistsException;
use LandingPayment\Usecase\CreateOrderCommand;
use LandingPayment\Usecase\CreateOrderUC;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreateOrderController {

    /**
     * @var CreateOrderFormFactory
     */
    private $createOrderFormFactory;

    /**
     * @var CreateOrderUC
     */
    private $createOrderUC;

    /**
     * @var OrderPaymentResponseFactory
     */
    private $orderPaymentResponseFactory;

    public function __construct(CreateOrderFormFactory $createOrderFormFactory,
                                CreateOrderUC $createOrderUC,
                                OrderPaymentResponseFactory $orderPaymentResponseFactory) {
        $this->createOrderFormFactory = $createOrderFormFactory;
        $this->createOrderUC = $createOrderUC;
        $this->orderPaymentResponseFactory = $orderPaymentResponseFactory;
    }

    public function createOrder(Request $request) {
        $form = $this->createOrderFormFactory->createForm();

        $form->handleRequest($request);

        if(!$form->isValid()) {
            return FormErrorResponseBuilder::createResponse($form);
        }

        /* @var $formData CreateOrderDto */
        $formData = $form->getData();

        $command = new CreateOrderCommand();
        $command->productId = $formData->productId;
        $command->email = $formData->email;
        $command->invoiceRequested = $formData->invoiceRequested;
        $command->invoiceTitle = $formData->invoiceTitle;
        $command->invoiceAddress = $formData->invoiceAddress;
        $command->invoiceNip = $formData->invoiceNip;
        $command->userIp = $request->getClientIp();

        try {
            $order = $this->createOrderUC->createOrder($command);
        }
        catch (ProductNotExistsException $e) {
            throw new NotFoundHttpException(null, $e);
        }

        return $this->orderPaymentResponseFactory->createPaymentResponse($order);
    }
}
