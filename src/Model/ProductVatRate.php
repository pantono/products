<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\DatabaseTable;

#[DatabaseTable(table: 'product_vat_rate', idColumn: 'id')]
class ProductVatRate
{
    private ?int $id = null;
    private string $name;
    private float $rate;

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

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): void
    {
        $this->rate = $rate;
    }

    public function addToPrice(float $price): float
    {
        return $price + ($price * $this->getRate());
    }

    public function calculateNet(float $price): float
    {
        return $price / (1 + $this->getRate());
    }
}
