<?php

namespace App\Service\Purchase;

use App\Entity\Purchase;
use App\Enum\PurchaseStatus;
use App\Repository\PurchaseRepository;
use DateTimeImmutable;
use RuntimeException;
use Throwable;

class PaymentManager
{
    /**
     * @var PaymentProcessorInterface[]
     */
    protected array $paymentProcessors = [];

    public function __construct(protected PurchaseRepository $purchaseRepository)
    {
        $this->registerProcessor('stripe' , new StripePaymentAdapter());
        $this->registerProcessor('paypal' , new PaypalPaymentAdapter());
    }

    public function isProcessorRegistered(string $paymentProcessorName): bool
    {
        return in_array($paymentProcessorName, $this->getProcessorsNames(), true);
    }

    public function getProcessorsNames(): array
    {
        return array_keys($this->paymentProcessors);
    }

    public function registerProcessor(string $processorName, PaymentProcessorInterface $processor): void
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
