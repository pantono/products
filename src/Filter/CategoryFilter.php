<?php

namespace Pantono\Products\Filter;

use Pantono\Database\Traits\Pageable;
use Pantono\Contracts\Filter\PageableInterface;
use Pantono\Database\Traits\ColumnFilter;

class CategoryFilter implements PageableInterface
{
    use Pageable, ColumnFilter;

    private ?string $slug = null;
    private ?string $search = null;
    private ?int $parentId = null;
    private string $orderBy = 'parent.display_order,c.display_order';

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): void
    {
        $this->slug = $slug;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): void
    {
        $this->search = $search;
    }

    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    public function setOrderBy(string $orderBy): void
    {
        $this->orderBy = $orderBy;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(?int $parentId): void
    {
        $this->parentId = $parentId;
    }
}
