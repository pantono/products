<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\Locator;
use Pantono\Products\Products;

#[Locator(methodName: 'getConditionById', className: Products::class)]
class ProductCondition
{
    private ?int $id = null;
    private string $name;
    private string $externalName;

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

    public function getExternalName(): string
    {
        return $this->externalName;
    }

    public function setExternalName(string $externalName): void
    {
        $this->externalName = $externalName;
    }
}
