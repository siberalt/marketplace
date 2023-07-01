<?php

namespace App\Validator;

use App\Service\TaxNumberService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class TaxNumberValidator extends ConstraintValidator
{
    protected TaxNumberService $taxNumberService;

    public function __construct(TaxNumberService $taxNumberService)
    {
        $this->taxNumberService = $taxNumberService;
    }

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$constraint instanceof TaxNumber) {
            throw new UnexpectedTypeException($constraint, TaxNumber::class);
        }

        if (empty($value)) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (null === $this->taxNumberService->parseCountryIso($value)) {
            $this->context->addViolation('Tax number is invalid');
        }
    }
}
