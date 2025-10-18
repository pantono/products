<?php

namespace Pantono\Products\Model;

use Pantono\Database\Traits\SavableModel;
use Pantono\Contracts\Attributes\FieldName;
use Pantono\Contracts\Attributes\Locator;
use Pantono\Products\Discounts;

class SpecialOffer
{
    use SavableModel;

    private ?int $id = null;
    #[FieldName('discount_id'), Locator(methodName: 'getDiscountById', className: Discounts::class)]
    private ?Discount $discount = null;
    private \DateTimeInterface $startDate;
    private \DateTimeInterface $endDate;
    private bool $active;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function setDiscount(?Discount $discount): void
    {
        $this->discount = $discount;
    }

    public function getStartDate(): \DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): \DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
