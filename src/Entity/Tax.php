<?php

namespace App\Entity;

use App\Repository\TaxRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

#[ORM\Entity(repositoryClass: TaxRepository::class)]
class Tax
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[JMS\Groups(groups: ['response'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[JMS\Groups(groups: ['response', 'request'])]
    private ?string $countryIso = null;

    #[ORM\Column(length: 255)]
    #[JMS\Groups(groups: ['response', 'request'])]
    private ?string $format = null;

    #[ORM\Column]
    #[JMS\Groups(groups: ['response', 'request'])]
    private ?int $percent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountryIso(): ?string
    {
        return $this->countryIso;
    }

    public function setCountryIso(string $countryIso): static
    {
        $this->countryIso = $countryIso;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function getPercent(): ?int
    {
        return $this->percent;
    }

    public function setPercent(int $percent): static
    {
        $this->percent = $percent;

        return $this;
    }
}
