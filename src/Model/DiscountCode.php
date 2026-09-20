<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\FieldName;
use Pantono\Database\Traits\SavableModel;
use Pantono\Contracts\Attributes\DatabaseTable;
use Pantono\Contracts\Attributes\Database\OneToOne;

#[DatabaseTable(table: 'discount_code', idColumn: 'id')]
class DiscountCode
{
    use SavableModel;

    private ?int $id = null;
    private string $code;
    #[FieldName('discount_id'), OneToOne(targetModel: Discount::class)]
    private ?Discount $discount = null;
    private \DateTimeImmutable $startDate;
    private \DateTimeImmutable $endDate;
    private int $maxUses;
    private bool $deleted = false;

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

    public function getEndDate(): \DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeImmutable $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getStartDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeImmutable $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getMaxUses(): int
    {
        return $this->maxUses;
    }

    public function setMaxUses(int $maxUses): void
    {
        $this->maxUses = $maxUses;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): void
    {
        $this->deleted = $deleted;
    }
}
