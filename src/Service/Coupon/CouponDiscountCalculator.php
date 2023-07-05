<?php

namespace App\Service\Coupon;

use App\Entity\Purchase;

interface CouponDiscountCalculator
{
    public function canHandle(Purchase $purchase): bool;

    public function calculate(Purchase $purchase): int;
}
