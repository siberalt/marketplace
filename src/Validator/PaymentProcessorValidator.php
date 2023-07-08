<?php

namespace App\Validator;

use App\Service\Purchase\PaymentManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class PaymentProcessorValidator extends ConstraintValidator
{
    public function __construct(protected PaymentManager $paymentManager)
    {
    }

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof PaymentProcessor) {
            throw new UnexpectedTypeException($constraint, PaymentProcessor::class);
        }

        if (empty($value)) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!$this->paymentManager->isProcessorRegistered($value)) {
            $this->context->addViolation('Payment processor is invalid');
        }
    }
}
