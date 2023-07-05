<?php

namespace App\Service\Coupon;

use App\Entity\Purchase;
use App\Enum\CouponType;

class FixedDiscountCalculator implements CouponDiscountCalculator
{
    public function canHandle(Purchase $purchase): bool
    {
        return $purchase->getCoupon()?->getType() === CouponType::FIXED;
    }

    public function calculate(Purchase $purchase): int
    {
        return $purchase->getCoupon()->getValue();
    }
}
