<?php

namespace Pantono\Products\Model;

class DiscountBase
{
    private ?int $id = null;
    private string $name;
    private bool $percentage;
    private bool $amount;
    private bool $freeDelivery;
    private bool $buyXGetY;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function isPercentage(): bool
    {
        return $this->percentage;
    }

    public function setPercentage(bool $percentage): void
    {
        $this->percentage = $percentage;
    }

    public function isAmount(): bool
    {
        return $this->amount;
    }

    public function setAmount(bool $amount): void
    {
        $this->amount = $amount;
    }

    public function isFreeDelivery(): bool
    {
        return $this->freeDelivery;
    }

    public function setFreeDelivery(bool $freeDelivery): void
    {
        $this->freeDelivery = $freeDelivery;
    }

    public function isBuyXGetY(): bool
    {
        return $this->buyXGetY;
    }

    public function setBuyXGetY(bool $buyXGetY): void
    {
        $this->buyXGetY = $buyXGetY;
    }
}
