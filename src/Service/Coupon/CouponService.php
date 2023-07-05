<?php

namespace App\Service\Coupon;

use App\Entity\Purchase;
use App\Repository\CouponRepository;

class CouponService
{
    /**
     * @var CouponDiscountCalculator[]
     */
    protected array $discountCalculators = [];

    protected CouponRepository $couponRepository;

    public function __construct(CouponRepository $couponRepository)
    {
        $this->addDiscountCalculator(new FixedDiscountCalculator());
        $this->addDiscountCalculator(new PercentDiscountCalculator());
        $this->couponRepository = $couponRepository;
    }

    public function addDiscountCalculator(CouponDiscountCalculator $discountCalculator): void
    {
        $this->discountCalculators[] = $discountCalculator;
    }

    public function calculateDiscount(Purchase $purchase): int
    {
        foreach ($this->discountCalculators as $calculator) {
            if ($calculator->canHandle($purchase)) {
                return $calculator->calculate($purchase);
            }
        }

        return 0;
    }
}
