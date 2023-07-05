<?php

namespace App\Service\Coupon;

use App\Entity\Purchase;
use App\Enum\CouponType;

class PercentDiscountCalculator implements CouponDiscountCalculator
{
    public function canHandle(Purchase $purchase): bool
    {
        return $purchase->getCoupon()?->getType() === CouponType::PERCENT;
    }

    public function calculate(Purchase $purchase): int
    {
        $coupon = $purchase->getCoupon();

        return ($purchase->getCost() * $coupon->getValue() / 100);
    }
}
