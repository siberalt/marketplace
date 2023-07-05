<?php

namespace App\Service;

use App\Entity\Purchase;
use App\Repository\TaxRepository;
use http\Exception\RuntimeException;

class TaxService
{
    protected array $taxNumberRegexes = [];

    protected TaxRepository $taxRepository;

    public function __construct(TaxRepository $taxRepository)
    {
        $taxes = $taxRepository->findAll();

        foreach ($taxes as $tax) {
            $this->addTaxNumberFormat($tax->getCountryIso(), $tax->getFormat());
        }

        $this->taxRepository = $taxRepository;
    }

    public function calculateTax(Purchase $purchase): int
    {
        $productCost = $purchase->getProduct()->getCost();
        $taxNumber = $purchase->getTaxNumber();
        $countryIso = $this->parseCountryIso($taxNumber);

        if (null === $countryIso) {
            throw new RuntimeException("Unknown tax number '$taxNumber'");
        }

        $tax = $this->taxRepository->findByCountryIso($countryIso);

        return ($productCost * $tax->getPercent() / 100);
    }

    public function addTaxNumberFormat(string $countryIso, string $format): static
    {
        $this->addTaxNumberRegex($countryIso, str_replace(['X', 'Y'], ['\d', '[a-zA-Z]'], $format));

        return $this;
    }

    public function addTaxNumberRegex(string $countryIso, string $format): void
    {
        $this->taxNumberRegexes[$countryIso] = $format;
    }

    public function parseCountryIso(string $taxNumber): ?string
    {
        foreach ($this->taxNumberRegexes as $countryIso => $regex) {
            if (preg_match($regex, $taxNumber)) {
                return $countryIso;
            }
        }

        return null;
    }
}
