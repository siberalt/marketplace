<?php

namespace App\Entity;

use App\Enum\CouponType;
use App\Repository\CouponRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use OpenApi\Attributes as OA;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as V;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
#[UniqueEntity(fields: 'code')]
class Coupon
{
    #[JMS\Groups(groups: ['response', 'make', 'cost'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[JMS\Groups(groups: ['response', 'request', 'make', 'cost'])]
    #[ORM\Column(length: 255, unique: true)]
    #[OA\Property(type: 'string', maxLength: 255)]
    #[V\NotBlank]
    private ?string $code = null;

    #[JMS\Groups(groups: ['response', 'request', 'make', 'cost'])]
    #[JMS\Type(CouponType::class)]
    #[ORM\Column(enumType: CouponType::class)]
    #[OA\Property(enum: CouponType::class)]
    #[V\NotBlank]
    private ?CouponType $type = null;

    /** @var int percent or amount */
    #[JMS\Groups(groups: ['response', 'request', 'make', 'cost'])]
    #[ORM\Column(options: ['default' => 0])]
    #[V\NotBlank]
    private int $value = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getType(): ?CouponType
    {
        return $this->type;
    }

    public function setType(CouponType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }
}
