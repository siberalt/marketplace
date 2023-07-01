<?php

namespace App\Validator;

use App\Helper\TaxNumberHelper;
use App\Repository\TaxRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class TaxNumberValidator extends ConstraintValidator
{
    protected TaxNumberHelper $taxNumberHelper;

    public function __construct(TaxRepository $taxRepository)
    {
        $taxNumberHelper = new TaxNumberHelper();
        $taxes = $taxRepository->findAll();

        foreach ($taxes as $tax) {
            $taxNumberHelper->addFormat($tax->getCountryIso(), $tax->getFormat());
        }

        $this->taxNumberHelper = $taxNumberHelper;
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

        if (null === $this->taxNumberHelper->parseCountryIso($value)) {
            $this->context->addViolation('Tax number is invalid');
        }
    }
}