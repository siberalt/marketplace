<?php

namespace App\Service;

use App\Entity\Purchase;
use App\Enum\CouponType;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Repository\TaxRepository;
use http\Exception\RuntimeException;

class PurchaseService
{
    protected TaxNumberService $taxNumberService;

    protected ProductRepository $productRepository;

    protected TaxRepository $taxRepository;

    protected CouponRepository $couponRepository;

    public function __construct(
        TaxNumberService  $taxNumberService,
        ProductRepository $productRepository,
        TaxRepository     $taxRepository,
        CouponRepository  $couponRepository,
    )
    {
        $this->taxNumberService = $taxNumberService;
        $this->productRepository = $productRepository;
        $this->taxRepository = $taxRepository;
        $this->couponRepository = $couponRepository;
    }

    public function calculateCost(Purchase $purchase): int
    {
        $productCost = $purchase->getProduct()->getCost();
        $taxNumber = $purchase->getTaxNumber();
        $couponCode = $purchase->getCouponCode();
        $countryIso = $this->taxNumberService->parseCountryIso($taxNumber);

        if (null === $countryIso) {
            throw new RuntimeException("Unknown tax number '$taxNumber'");
        }

        $tax = $this->taxRepository->findByCountryIso($countryIso);
        $cost = $productCost + ($productCost * $tax->getPercent() / 100);

        if (null !== $couponCode) {
            $coupon = $this->couponRepository->findByCouponCode($couponCode);

            if (null === $coupon) {
                throw new RuntimeException("Coupon with code '$couponCode' has not benn found");
            }

            $cost -= $coupon->getType() === CouponType::FIXED
                ? $coupon->getValue()
                : ($cost * $coupon->getValue() / 100);
        }

        return $cost;
    }

    public function makePurchase(Purchase $purchase)
    {

    }
}
