<?php

namespace App\Form\Transformers;

use App\Entity\Coupon;
use App\Repository\CouponRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CodeToCouponTransformer implements DataTransformerInterface
{
    public function __construct(
        protected CouponRepository $couponRepository
    )
    {}

    /**
     * @param Coupon $value
     * @return string
     */
    public function transform(mixed $value): string
    {
        if (null === $value) {
            return '';
        }

        return $value->getCode();
    }

    /**
     * @param string $value
     * @return Coupon|null
     */
    public function reverseTransform(mixed $value): ?Coupon
    {
        $coupon = $this->couponRepository->findOneByCouponCode($value);

        if (null === $coupon) {
            $exception = new TransformationFailedException("Coupon '$value' has not been found");
            $exception->setInvalidMessage(
                "Invalid couponCode '{{ couponCode }}'", ['{{ couponCode }}' => $value]
            );

            throw $exception;
        }

        return $coupon;
    }
}
