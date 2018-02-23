<?php

namespace LandingPayment\Infrastructure\Mail;

use Twig_Environment;
use InvalidArgumentException;
use LandingPayment\Domain\Order\Order;
use LandingPayment\Domain\Order\OrderPaidEvent;
use LandingPayment\Domain\Product\ProductRepository;

class OrderPaidEventListener
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var MailerFactory
     */
    private $mailerFactory;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * OrderPaidEventListener constructor.
     * @param ProductRepository $productRepository
     * @param MailerFactory $mailerFactory
     * @param Twig_Environment $twig
     */
    public function __construct(ProductRepository $productRepository,
                                MailerFactory $mailerFactory,
                                Twig_Environment $twig)
    {
        $this->productRepository = $productRepository;
        $this->mailerFactory = $mailerFactory;
        $this->twig = $twig;
    }


    public function onOrderPaid(OrderPaidEvent $event) {
        $order = $event->getOrder();
        $productId = $order->getItem()->getProductId();

        $product = $this->productRepository->getById($productId);

        if ($product === null) {
            throw new InvalidArgumentException("Product does not exists.");
        }

        $productOptions = $product->getMetadata();

        $recipient = $order->getOrderData()->getEmail();
        $contents = $this->createEmailContents($order);
        $subject = $productOptions['mail']['order-paid']['subject'];

        $mail = $this->mailerFactory->createMail($productId)
            ->setTo($recipient)
            ->setSubject($subject)
            ->setBody($contents)
            ->setContentType("text/html");

        $this->mailerFactory->createTransport($productId)
            ->send($mail);
    }

    private function createEmailContents(Order $order) {
        $productId = $order->getItem()->getProductId();
        $template = sprintf('product/%s/mail.order-paid.twig', $productId);

        return $this->twig->render($template, array(
            'order' => $order,
        ));
    }
}
