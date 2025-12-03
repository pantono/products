<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\Locator;
use Pantono\Products\Products;
use Pantono\Contracts\Attributes\FieldName;

class ProductField
{
    private ?int $id = null;
    private int $productVersionId;
    #[Locator(methodName: 'getFieldTypeById', className: Products::class), FieldName('type_id')]
    private ?ProductFieldType $type = null;
    private mixed $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getProductVersionId(): int
    {
        return $this->productVersionId;
    }

    public function setProductVersionId(int $productVersionId): void
    {
        $this->productVersionId = $productVersionId;
    }

    public function getType(): ?ProductFieldType
    {
        return $this->type;
    }

    public function setType(?ProductFieldType $type): void
    {
        $this->type = $type;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    public function getCastedValue(): mixed
    {
        if ($this->getType()?->getType() === 'integer') {
            return (int)$this->getValue();
        }
        if ($this->getType()?->getType() === 'float') {
            return (float)$this->getValue();
        }
        return $this->getValue();
    }
}
