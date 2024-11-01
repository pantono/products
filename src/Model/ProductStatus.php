<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\Locator;
use Pantono\Products\Products;

#[Locator(methodName: 'getProductStatusById', className: Products::class)]
class ProductStatus
{
    private ?int $id = null;
    private string $name;
    private bool $visible;
    private bool $purchasable;
    private bool $archived;

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

    public function isVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): void
    {
        $this->visible = $visible;
    }

    public function isPurchasable(): bool
    {
        return $this->purchasable;
    }

    public function setPurchasable(bool $purchasable): void
    {
        $this->purchasable = $purchasable;
    }

    public function isArchived(): bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): void
    {
        $this->archived = $archived;
    }
}
