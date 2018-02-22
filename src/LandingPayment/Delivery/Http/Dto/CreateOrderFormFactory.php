<?php

namespace LandingPayment\Delivery\Http\Dto;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactory;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;

class CreateOrderFormFactory
{
    const VG_INVOICE = 'invoice';

    /**
     * @var FormFactory
     */
    private $formFactory;

    public function __construct(FormFactory $formFactory) {
        $this->formFactory = $formFactory;
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm() {
        $validationGroups = function(FormInterface $form) {
            $groups = [Constraint::DEFAULT_GROUP];

            /* @var $data CreateOrderDto */
            $data = $form->getData();

            if($data->invoiceRequested) {
                $groups[] = self::VG_INVOICE;
            }

            return $groups;
        };

        $options = [
            'data_class' => CreateOrderDto::class,
            'validation_groups' => $validationGroups,
        ];

        return $this->formFactory->createNamedBuilder('order', FormType::class, null, $options)
            ->add('productId', TextType::class, [
                'constraints' => array(new Assert\NotBlank())
            ])
            ->add('email', EmailType::class, [
                'constraints' => array(new Assert\NotBlank())
            ])
            ->add('invoiceRequested', CheckboxType::class)
            ->add('invoiceTitle', TextType::class, [
                'constraints' => array(new Assert\NotBlank(['groups' => self::VG_INVOICE]))
            ])
            ->add('invoiceAddress', TextType::class, [
                'constraints' => array(new Assert\NotBlank(['groups' => self::VG_INVOICE]))
            ])
            ->add('invoiceNip', TextType::class, [
                'constraints' => array(new Assert\NotBlank(['groups' => self::VG_INVOICE]))
            ])
            ->getForm();
    }
}
