<?php

namespace Pantono\Products\Model;

use Pantono\Contracts\Attributes\FieldName;
use Pantono\Contracts\Attributes\Locator;
use Pantono\Products\Products;
use Pantono\Database\Traits\SavableModel;

#[Locator(methodName: 'getProductCategoryById', className: Products::class)]
class ProductCategory
{
    use SavableModel;

    private ?int $id = null;
    #[FieldName('category_id'), Locator(methodName: 'getCategoryById', className: Products::class)]
    private Category $category;
    private int $productId;
    private int $displayOrder;
    private bool $archived;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    public function getDisplayOrder(): int
    {
        return $this->displayOrder;
    }

    public function setDisplayOrder(int $displayOrder): void
    {
        $this->displayOrder = $displayOrder;
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
