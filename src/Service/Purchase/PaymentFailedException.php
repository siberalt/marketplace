<?php

namespace App\Service\Purchase;

use App\Entity\Purchase;
use Exception;
use Throwable;

class PaymentFailedException extends Exception
{
    protected Purchase $purchase;

    public function __construct(string $message, Purchase $purchase, int $code = 0, ?Throwable $previous = null)
    {
        $this->purchase = $purchase;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param Purchase $purchase
     */
    public function setPurchase(Purchase $purchase): void
    {
        $this->purchase = $purchase;
    }

    /**
     * @return Purchase
     */
    public function getPurchase(): Purchase
    {
        return $this->purchase;
    }
}
