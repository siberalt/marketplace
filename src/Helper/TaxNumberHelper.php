<?php

namespace App\Helper;

class TaxNumberHelper
{
    protected array $regexes = [];

    public function addFormat(string $countryIso, string $format): static
    {
        $this->addRegex($countryIso, str_replace(['X', 'Y'], ['\d', '[a-zA-Z]'], $format));

        return $this;
    }

    public function addRegex(string $countryIso, string $format): void
    {
        $this->regexes[$countryIso] = $format;
    }

    public function parseCountryIso(string $taxNumber): ?string
    {
        foreach ($this->regexes as $countryIso => $regex) {
            if (preg_match($regex, $taxNumber)) {
                return $countryIso;
            }
        }

        return null;
    }

    /**
     * @param array $regexes
     */
    public function setRegexes(array $regexes): void
    {
        $this->regexes = $regexes;
    }

    /**
     * @return array
     */
    public function getRegexes(): array
    {
        return $this->regexes;
    }
}
