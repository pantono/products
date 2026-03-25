<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\FieldName;
use Pantono\Utilities\DateTimeParser;
use Pantono\Contracts\Attributes\Database\OneToOne;

class ProductField
{
    private ?int $id = null;
    private int $productVersionId;
    #[OneToOne(targetModel: ProductFieldType::class), FieldName('type_id')]
    private ?ProductFieldType $type = null;
    private string $value;

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

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getCastedValue(): mixed
    {
        $type = $this->getType()?->getType();
        if ($type === 'integer') {
            return (int)$this->getValue();
        }
        if ($type === 'float' || $type === 'number') {
            return (float)$this->getValue();
        }
        if ($type === 'boolean') {
            return (bool)$this->getValue();
        }
        if ($type === 'date') {
            return DateTimeParser::parseDate($this->getValue())?->format('Y-m-d');
        }
        return $this->getValue();
    }
}
