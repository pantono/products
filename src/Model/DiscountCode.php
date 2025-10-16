<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\FieldName;
use Pantono\Database\Traits\SavableModel;

class DiscountCode
{
    use SavableModel;

    private ?int $id = null;
    private string $code;
    #[FieldName('discount_id')]
    private ?Discount $discount = null;
    private ?\DateTime $startDate = null;
    private ?\DateTime $endDate = null;
    private int $maxUses;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function setDiscount(?Discount $discount): void
    {
        $this->discount = $discount;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getMaxUses(): int
    {
        return $this->maxUses;
    }

    public function setMaxUses(int $maxUses): void
    {
        $this->maxUses = $maxUses;
    }
}
