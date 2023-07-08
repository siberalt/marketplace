<?php

namespace App\Form;

use App\Entity\Coupon;
use App\Entity\Purchase;
use App\Form\Transformers\CodeToCouponTransformer;
use App\Form\Transformers\IdToProductTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PurchaseForm extends AbstractType
{
    public function __construct(
        protected IdToProductTransformer $productTransformer,
        protected CodeToCouponTransformer $couponTransformer
    )
    {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('taxNumber')
            ->add('couponCode', options: [
                'setter' => function(Purchase $purchase, ?Coupon $coupon) {
                    $purchase->setCoupon($coupon);
                },
                'getter' => function(Purchase $purchase): ?Coupon {
                    return $purchase->getCoupon();
                }
            ])
            ->add('paymentProcessor')
            ->add('product')
        ;

        $builder->get('product')
            ->addModelTransformer($this->productTransformer)
            ->resetViewTransformers();
        $builder->get('couponCode')
            ->addModelTransformer($this->couponTransformer)
            ->resetViewTransformers();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Purchase::class,
        ]);
    }
}
