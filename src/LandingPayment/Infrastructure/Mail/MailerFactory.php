<?php

namespace LandingPayment\Infrastructure\Mail;

use Swift_Mailer;
use Swift_Message;
use Swift_Transport;
use LandingPayment\Domain\Product\ProductRepository;

class MailerFactory
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
    }

    /**
     * @param string $productId
     * @return Swift_Mailer
     */
    public function createTransport($productId) {
        $options = $this->getOptionsByProduct($productId);

        $transport = new \Swift_Transport_EsmtpTransport(
            new \Swift_Transport_StreamBuffer(new \Swift_StreamFilters_StringReplacementFilterFactory()),
            array(
                new \Swift_Transport_Esmtp_AuthHandler(array(
                    new \Swift_Transport_Esmtp_Auth_CramMd5Authenticator(),
                    new \Swift_Transport_Esmtp_Auth_LoginAuthenticator(),
                    new \Swift_Transport_Esmtp_Auth_PlainAuthenticator(),
                ))
            ),
            new \Swift_Events_SimpleEventDispatcher()
        );

        $options = array_replace(array(
            'host' => 'localhost',
            'port' => 25,
            'username' => '',
            'password' => '',
            'encryption' => null,
            'auth_mode' => null,
            'stream_context_options' => [],
        ), $options);

        $transport->setHost($options['host']);
        $transport->setPort($options['port']);
        $transport->setEncryption($options['encryption']);
        $transport->setUsername($options['username']);
        $transport->setPassword($options['password']);
        $transport->setAuthMode($options['auth_mode']);
        $transport->setStreamOptions($options['stream_context_options']);

        return new Swift_Mailer($transport);
    }

    /**
     * @param string $productId
     * @return Swift_Message
     */
    public function createMail($productId) {
        $options = $this->getOptionsByProduct($productId);

        return Swift_Message::newInstance()
            ->setFrom($options['from'], $options['from_name']);
    }

    /**
     * @param $productId
     * @return array
     */
    private function getOptionsByProduct($productId) {
        $product = $this->productRepository->getById($productId);

        if ($product === null) {
            return null;
        }

        $options = $product->getMetadata();
        return $options['mailer'];
    }
}
