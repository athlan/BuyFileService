<?php

namespace LandingPayment\Delivery\Http\Response;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

class FormErrorResponseBuilder
{
    /**
     * @param FormInterface $form
     * @return Response
     */
    public static function createResponse(FormInterface $form) {
        $body = $form->getErrors(true, false)->__toString();
        return Response::create($body, Response::HTTP_OK);
    }
}
