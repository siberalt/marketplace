<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Purchase;
use Doctrine\Persistence\ObjectManager;
use FOS\RestBundle\Form\Transformer\EntityToIdObjectTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PurchaseForm extends AbstractType
{
    protected ObjectManager $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('taxNumber')
            ->add('couponCode')
            ->add('paymentProcessor')
            ->add('product')
        ;

        $builder->get('product')
            ->addModelTransformer(new EntityToIdObjectTransformer($this->objectManager, Product::class));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Purchase::class,
        ]);
    }
}
