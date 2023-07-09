<?php

namespace App\Tests\Service\Purchase;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\Purchase;
use App\Entity\Tax;
use App\Enum\CouponType;
use App\Enum\PurchaseStatus;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Repository\TaxRepository;
use App\Service\Purchase\PaymentFailedException;
use App\Service\Purchase\PurchaseService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PurchaseServiceTest extends KernelTestCase
{
    /**
     * @throws Exception
     */
    public function testWithNoCoupon(): void
    {
        self::bootKernel();

        $product = (new Product())
            ->setCost(200)
            ->setName('laptop');

        $this->saveProduct($product);
        $this->saveTax(
            (new Tax())
                ->setCountryIso('IT')
                ->setFormat('ITXXXXXXXXXXX')
                ->setPercent(22)
        );

        $purchase = (new Purchase())
            ->setProduct($product)
            ->setTaxNumber('IT12345678901')
            ->setPaymentProcessor('stripe');

        $this->testPurchaseService($purchase, 244);
    }

    /**
     * @throws Exception
     */
    public function testWithFixedCoupon(): void
    {
        self::bootKernel();

        $product = (new Product())
            ->setCost(100)
            ->setName('iphone');
        $coupon = (new Coupon())
            ->setType(CouponType::FIXED)
            ->setValue(50)
            ->setCode('Fixed');

        $this->saveProduct($product);
        $this->saveTax(
            (new Tax())
                ->setCountryIso('DE')
                ->setFormat('DEXXXXXXXXX')
                ->setPercent(19),
        );
        $this->saveCoupon($coupon);

        $purchase = (new Purchase())
            ->setProduct($product)
            ->setTaxNumber('DE123333442')
            ->setPaymentProcessor('paypal')
            ->setCoupon($coupon);

        $this->testPurchaseService($purchase, 69);
    }

    /**
     * @throws Exception
     */
    public function testWithPercentCoupon(): void
    {
        self::bootKernel();

        $product = (new Product())
            ->setCost(32000)
            ->setName('car');
        $coupon = (new Coupon())
            ->setType(CouponType::PERCENT)
            ->setValue(15)
            ->setCode('Percent');

        $this->saveProduct($product);
        $this->saveTax(
            (new Tax())
                ->setCountryIso('GR')
                ->setFormat('GRXXXXXXXXX')
                ->setPercent(24),
        );
        $this->saveCoupon($coupon);

        $purchase = (new Purchase())
            ->setProduct($product)
            ->setTaxNumber('GR123456789')
            ->setPaymentProcessor('stripe')
            ->setCoupon($coupon);

        $this->testPurchaseService($purchase, 33728);
    }

    /**
     * @throws PaymentFailedException
     * @throws Exception
     */
    protected function testPurchaseService(Purchase $purchase, int $expectedCost)
    {
        /** @var PurchaseService $purchaseService */
        $purchaseService = self::getContainer()->get(PurchaseService::class);
        $purchaseService->makePurchase($purchase);

        $this->assertEquals($expectedCost, $purchase->getCost(), 'Cost is invalid');
        $this->assertEquals(PurchaseStatus::ACCEPTED, $purchase->getStatus(), 'Error has occurred');
        $this->assertNotEmpty($purchase->getCreatedAt(), 'CreatedAt is empty');
    }

    /**
     * @throws Exception
     */
    protected function saveProduct(Product $product)
    {
        /** @var ProductRepository $productRepository */
        $productRepository = static::getContainer()->get(ProductRepository::class);
        $productRepository->save($product, true);
    }

    /**
     * @throws Exception
     */
    protected function saveTax(Tax $tax)
    {
        /** @var TaxRepository $taxRepository */
        $taxRepository = static::getContainer()->get(TaxRepository::class);
        $taxRepository->save($tax, true);
    }

    /**
     * @throws Exception
     */
    protected function saveCoupon(Coupon $coupon)
    {
        /** @var CouponRepository $couponRepository */
        $couponRepository = static::getContainer()->get(CouponRepository::class);
        $couponRepository->save($coupon, true);
    }
}
