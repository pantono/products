<?php

namespace Pantono\Products\Filter;

use Pantono\Database\Traits\Pageable;
use Pantono\Contracts\Filter\PageableInterface;
use Pantono\Products\Model\ProductStatus;
use Pantono\Products\Model\Category;
use Pantono\Database\Traits\ColumnFilter;

class ProductFilter implements PageableInterface
{
    use Pageable, ColumnFilter;

    private ?ProductStatus $status = null;
    private ?string $search = null;
    private array $categoryIds = [];
    private string $orderBy = 'published.date_added DESC';


    public function getStatus(): ?ProductStatus
    {
        return $this->status;
    }

    public function setStatus(?ProductStatus $status): void
    {
        $this->status = $status;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): void
    {
        $this->search = $search;
    }

    public function getCategoryIds(): array
    {
        return $this->categoryIds;
    }

    public function setCategoryIds(array $categoryIds): void
    {
        $this->categoryIds = $categoryIds;
    }

    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    public function setOrderBy(string $orderBy): void
    {
        $this->orderBy = $orderBy;
    }
}
