<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use App\Validator\TaxNumber;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
class Purchase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column(length: 255)]
    #[TaxNumber]
    private ?string $taxNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $couponCode = null;

    #[ORM\Column(length: 255)]
    private ?string $paymentProcessor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getTaxNumber(): ?string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(string $taxNumber): static
    {
        $this->taxNumber = $taxNumber;

        return $this;
    }

    public function getCouponCode(): ?string
    {
        return $this->couponCode;
    }

    public function setCouponCode(string $couponCode): static
    {
        $this->couponCode = $couponCode;

        return $this;
    }

    public function getPaymentProcessor(): ?string
    {
        return $this->paymentProcessor;
    }

    public function setPaymentProcessor(string $paymentProcessor): static
    {
        $this->paymentProcessor = $paymentProcessor;

        return $this;
    }
}
