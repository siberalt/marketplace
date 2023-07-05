<?php

namespace App\Service\Purchase;

use App\Entity\Purchase;

interface PaymentProcessorInterface
{
    /**
     * @param Purchase $purchase
     * @return void
     *
     * @throws PaymentFailedException
     */
    public function process(Purchase $purchase): void;
}