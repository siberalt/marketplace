<?php

namespace App\Service\Purchase;

use App\Entity\Purchase;
use Exception;

require_once ('External/StripePaymentProcessor.php');

class PaypalPaymentAdapter implements PaymentProcessorInterface
{
    protected \PaypalPaymentProcessor $paypalPaymentProcessor;

    public function __construct()
    {
        $this->paypalPaymentProcessor = new \PaypalPaymentProcessor();
    }

    /**
     * @throws Exception
     */
    public function process(Purchase $purchase): void
    {
        $this->paypalPaymentProcessor->pay($purchase->getCost());
    }
}
