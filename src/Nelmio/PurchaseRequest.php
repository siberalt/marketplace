<?php

namespace App\Nelmio;

class PurchaseRequest
{
    protected int $product;

    protected string $taxNumber;

    protected string $couponCode;

    protected string $paymentProcessor;
}
