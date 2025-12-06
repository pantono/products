<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\Locator;
use Pantono\Products\Categories;

class CategoryField
{
    private int $categoryId;
    #[Locator(methodName: 'getFieldTypeById', className: Categories::class)]
    private CategoryFieldType $type;
    private string $value;

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $categoryId): void
    {
        $this->categoryId = $categoryId;
    }

    public function getType(): CategoryFieldType
    {
        return $this->type;
    }

    public function setType(CategoryFieldType $type): void
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
