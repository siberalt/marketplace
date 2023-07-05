<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Purchase;
use App\Form\Transformers\CodeToCouponTransformer;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Form\Transformer\EntityToIdObjectTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PurchaseForm extends AbstractType
{
    public function __construct(
        protected ManagerRegistry $objectManager,
        protected CodeToCouponTransformer $couponTransformer
    )
    {}

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
        $builder->get('couponCode')
            ->addModelTransformer($this->couponTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Purchase::class,
        ]);
    }
}
