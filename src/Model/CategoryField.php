<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\Locator;
use Pantono\Products\Categories;
use Pantono\Contracts\Attributes\FieldName;

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
}
