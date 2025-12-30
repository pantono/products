<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\Locator;
use Pantono\Products\Categories;
use Pantono\Contracts\Attributes\FieldName;
use Pantono\Utilities\DateTimeParser;

class CategoryField
{
    private int $categoryId;
    #[Locator(methodName: 'getFieldTypeById', className: Categories::class), FieldName('type_id')]
    private ?CategoryFieldType $type = null;
    private string $value;

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function getType(): ?CategoryFieldType
    {
        return $this->type;
    }

    public function setType(?CategoryFieldType $type): void
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
        $value = $this->getValue();
        if ($type === 'boolean') {
            return (bool)$value;
        }
        if ($type === 'integer') {
            return (int)$value;
        }
        if ($type === 'float') {
            return (float)$value;
        }
        if ($type === 'date' || $type === 'datetime') {
            return DateTimeParser::parseDateImmutable($value);
        }
        if ($type === 'json') {
            return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        }
        if ($type === 'array') {
            return explode(',', $value);
        }
        return $value;
    }
}
