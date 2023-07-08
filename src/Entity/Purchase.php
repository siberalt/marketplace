<?php

namespace App\Entity;

use App\Enum\PurchaseStatus;
use App\Repository\PurchaseRepository;
use App\Validator\PaymentProcessor;
use App\Validator\TaxNumber;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
class Purchase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[JMS\Groups(groups: ['make'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[JMS\Groups(groups: ['make', 'cost'])]
    private ?Product $product = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    #[TaxNumber]
    private ?string $taxNumber = null;

    #[ORM\Column(length: 255)]
    #[NotBlank]
    #[PaymentProcessor]
    #[JMS\Groups(groups: ['make', 'cost'])]
    private ?string $paymentProcessor = null;

    #[ORM\ManyToOne]
    #[JMS\Groups(groups: ['make', 'cost'])]
    private ?Coupon $coupon = null;

    #[ORM\Column]
    #[JMS\Groups(groups: ['make', 'cost'])]
    private ?int $cost = null;

    #[ORM\Column]
    #[JMS\Groups(groups: ['make'])]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(enumType: PurchaseStatus::class)]
    #[JMS\Groups(groups: ['make'])]
    private ?PurchaseStatus $status = null;

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

    public function getPaymentProcessor(): ?string
    {
        return $this->paymentProcessor;
    }

    public function setPaymentProcessor(string $paymentProcessor): static
    {
        $this->paymentProcessor = $paymentProcessor;

        return $this;
    }

    public function getCoupon(): ?Coupon
    {
        return $this->coupon;
    }

    public function setCoupon(?Coupon $coupon): static
    {
        $this->coupon = $coupon;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(?int $cost): static
    {
        $this->cost = $cost;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatus(): ?PurchaseStatus
    {
        return $this->status;
    }

    public function setStatus(PurchaseStatus $status): static
    {
        $this->status = $status;

        return $this;
    }
}
