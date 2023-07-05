<?php

namespace App\Service\Purchase;

use App\Entity\Purchase;
use App\Enum\PurchaseStatus;
use App\Repository\PurchaseRepository;
use DateTimeImmutable;
use http\Exception\RuntimeException;
use Throwable;

class PaymentManager
{
    /**
     * @var PaymentProcessorInterface[]
     */
    protected array $paymentProcessors = [];

    public function __construct(protected PurchaseRepository $purchaseRepository)
    {
        $this->addPaymentProcessor('stripe' , new StripePaymentAdapter());
        $this->addPaymentProcessor('paypal' , new PaypalPaymentAdapter());
    }

    public function getProcessorsNames(): array
    {
        return array_keys($this->paymentProcessors);
    }

    public function addPaymentProcessor(string $processorName, PaymentProcessorInterface $processor): void
    {
        $this->paymentProcessors[$processorName] = $processor;
    }

    /**
     * @throws PaymentFailedException
     */
    public function process(Purchase $purchase): void
    {
        $processorName = $purchase->getPaymentProcessor();

        if (!isset($this->paymentProcessors[$processorName])) {
            throw new RuntimeException("Payment processor '$processorName' has not been found");
        }

        $processor = $this->paymentProcessors[$processorName];

        try {
            $processor->process($purchase);
        } catch (PaymentFailedException $exception) {
            $this->onPaymentFailure($purchase);

            throw $exception;
        } catch (Throwable $exception) {
            $this->onPaymentFailure($purchase);

            throw new PaymentFailedException($exception->getMessage(), $purchase);
        }

        $this->onPaymentSuccess($purchase);
    }

    protected function onPaymentSuccess(Purchase $purchase): void
    {
        $purchase->setCreatedAt(new DateTimeImmutable());
        $purchase->setStatus(PurchaseStatus::ACCEPTED);
    }

    protected function onPaymentFailure(Purchase $purchase): void
    {
        $purchase->setCreatedAt(new DateTimeImmutable());
        $purchase->setStatus(PurchaseStatus::ERROR);
    }
}
