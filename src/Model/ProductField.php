<?php

namespace Pantono\Products\Model;

class ProductField
{
    private ?int $id = null;
    private int $productVersionId;
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
