<?php

namespace App\Service\Purchase;

use App\Entity\Purchase;

require_once ('External/StripePaymentProcessor.php');

class StripePaymentAdapter implements PaymentProcessorInterface
{
    protected \StripePaymentProcessor $stripePaymentProcessor;

    public function __construct()
    {
        $this->stripePaymentProcessor = new \StripePaymentProcessor();
    }

    /**
     * @throws PaymentFailedException
     */
    public function process(Purchase $purchase): void
    {
        $success = $this->stripePaymentProcessor->processPayment($purchase->getCost());

        if (!$success) {
            throw new PaymentFailedException('Stripe payment has been failed', $purchase);
        }
    }
}
