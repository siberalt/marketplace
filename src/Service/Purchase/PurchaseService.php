<?php

namespace App\Service\Purchase;

use App\Entity\Purchase;
use App\Repository\ProductRepository;
use App\Repository\PurchaseRepository;
use App\Service\Coupon\CouponService;
use App\Service\TaxService;

class PurchaseService
{
    public function __construct(
        protected TaxService         $taxService,
        protected ProductRepository  $productRepository,
        protected CouponService      $couponService,
        protected PaymentManager     $paymentManager,
        protected PurchaseRepository $purchaseRepository
    )
    {
    }

    public function calculateCost(Purchase $purchase): int
    {
        $oldCost = $purchase->getCost();
        $cost = $purchase->getProduct()->getCost();
        $cost += $this->taxService->calculateTax($purchase);
        $purchase->setCost($cost);
        $cost = max($cost - $this->couponService->calculateDiscount($purchase), 0);
        $purchase->setCost($oldCost);

        return $cost;
    }

    /**
     * @throws PaymentFailedException
     */
    public function makePurchase(Purchase $purchase): void
    {
        $cost = $this->calculateCost($purchase);
        $purchase->setCost($cost);
        $this->paymentManager->process($purchase);
        $this->purchaseRepository->save($purchase, true);
    }
}
